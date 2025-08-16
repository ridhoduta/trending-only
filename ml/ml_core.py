# C:\Ridho\Projek\Meachine Learning\recommender_api\ml_core.py

import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import linear_kernel
import mysql.connector
import joblib
import os
from config import Config
import sys # Import sys untuk print ke stderr

class MLRecommender:
    def __init__(self):
        self.articles_df = None
        self.tfidf_vectorizer = None
        self.cosine_sim_matrix = None
        self.indices = None
        self.load_models()
        # Tambahan: Jika setelah load_models() articles_df masih None (misal model belum ada),
        # maka coba ambil data artikel langsung.
        if self.articles_df is None or self.articles_df.empty:
            print("Artikel DataFrame kosong setelah memuat model, mencoba mengambil data artikel.")
            self.fetch_articles_data()
            # Jika data berhasil diambil, latih dan simpan model baru
            if self.articles_df is not None and not self.articles_df.empty:
                print("Data artikel berhasil diambil. Melatih dan menyimpan model baru.")
                self.train_and_save_models()


    def _get_db_connection(self):
        """Membuat koneksi ke database."""
        try:
            return mysql.connector.connect(**Config.DATABASE)
        except mysql.connector.Error as err:
            print(f"Error koneksi database: {err}", file=sys.stderr)
            raise # Re-raise exception agar bisa ditangkap di layer atas

    def fetch_articles_data(self):
        """Mengambil data artikel dari database."""
        conn = None
        cursor = None
        try:
            conn = self._get_db_connection()
            cursor = conn.cursor(dictionary=True)
            # Pastikan mengambil 'id_artikel', 'judul_id', 'slug_id' untuk rekomendasi
            cursor.execute("""
                SELECT a.id_artikel, a.judul_id, a.slug_id, a.konten_id, a.tags_id, a.views, k.nama_kategori_id
                FROM tb_artikel a
                LEFT JOIN tb_kategori k ON a.id_kategori = k.id_kategori
            """)
            articles = cursor.fetchall()
            
            if not articles:
                self.articles_df = pd.DataFrame() # Set ke DataFrame kosong
                print("Peringatan: Tidak ada data artikel yang ditemukan dari database.")
                return

            self.articles_df = pd.DataFrame(articles)
            # Pastikan semua kolom yang digabungkan tidak NaN/None
            self.articles_df['content'] = self.articles_df['judul_id'].fillna('') + ' ' + \
                                          self.articles_df['konten_id'].fillna('') + ' ' + \
                                          self.articles_df['tags_id'].fillna('') + ' ' + \
                                          self.articles_df['nama_kategori_id'].fillna('')
            
            # Membangun ulang indeks setiap kali data artikel diambil
            self.indices = pd.Series(self.articles_df.index, index=self.articles_df['id_artikel']).drop_duplicates()
            
            print("Data artikel berhasil diambil dan diproses.")
        except Exception as e:
            print(f"Error fetching articles: {e}", file=sys.stderr)
            self.articles_df = None # Set None jika terjadi error
        finally:
            if cursor: cursor.close()
            if conn: conn.close()

    def train_and_save_models(self):
        """
        Melatih TF-IDF dan menghitung Cosine Similarity, lalu menyimpan model.
        Fungsi ini biasanya dipanggil oleh model_updater.py atau saat inisialisasi jika model belum ada.
        """
        if self.articles_df is None or self.articles_df.empty:
            print("Tidak ada data artikel untuk dilatih. Ambil data terlebih dahulu.", file=sys.stderr)
            return

        print("Melatih TF-IDF Vectorizer...")
        # Anda dapat menambahkan stop_words jika diinginkan:
        # from sklearn.feature_extraction.text import TfidfVectorizer
        # INDONESIAN_STOP_WORDS = ["yang", "dan", "di", "ke", ...] # Define your list
        # self.tfidf_vectorizer = TfidfVectorizer(stop_words=INDONESIAN_STOP_WORDS)
        self.tfidf_vectorizer = TfidfVectorizer() 
        tfidf_matrix = self.tfidf_vectorizer.fit_transform(self.articles_df['content'])

        print("Menghitung Cosine Similarity Matrix...")
        self.cosine_sim_matrix = linear_kernel(tfidf_matrix, tfidf_matrix)

        # Indeks juga harus diperbarui saat model dilatih/data diambil
        self.indices = pd.Series(self.articles_df.index, index=self.articles_df['id_artikel']).drop_duplicates()

        os.makedirs(Config.MODEL_DIR, exist_ok=True)
        joblib.dump(self.tfidf_vectorizer, os.path.join(Config.MODEL_DIR, 'tfidf_vectorizer.joblib'))
        joblib.dump(self.cosine_sim_matrix, os.path.join(Config.MODEL_DIR, 'cosine_sim_matrix.joblib'))
        joblib.dump(self.indices, os.path.join(Config.MODEL_DIR, 'indices.joblib'))
        print("Model TF-IDF, Cosine Similarity, dan Index berhasil disimpan.")

    def load_models(self):
        """Memuat model yang sudah disimpan."""
        try:
            self.tfidf_vectorizer = joblib.load(os.path.join(Config.MODEL_DIR, 'tfidf_vectorizer.joblib'))
            self.cosine_sim_matrix = joblib.load(os.path.join(Config.MODEL_DIR, 'cosine_sim_matrix.joblib'))
            self.indices = joblib.load(os.path.join(Config.MODEL_DIR, 'indices.joblib'))
            print("Model berhasil dimuat dari file.")
            # PENTING: Ambil data artikel TERBARU setelah memuat model
            # Ini memastikan self.articles_df sinkron dengan self.indices dan matrix
            self.fetch_articles_data() 
        except FileNotFoundError:
            print("Model belum ditemukan. Harap latih model terlebih dahulu menggunakan model_updater.py", file=sys.stderr)
            # Tidak memanggil fetch_articles_data di sini karena sudah ada di __init__
            # untuk menangani kasus ini, dan juga untuk menghindari rekursi tak terbatas.
        except Exception as e:
            print(f"Error loading models: {e}", file=sys.stderr)
            self.articles_df = None # Pastikan articles_df diset None jika ada error


    def get_recommendations(self, article_id, num_recommendations=5):
        """
        Mengembalikan rekomendasi artikel berdasarkan id_artikel yang diberikan.
        Mengembalikan daftar dictionary {'id_artikel', 'judul_id', 'slug_id'}.
        """
        if self.articles_df is None or self.articles_df.empty or \
           self.tfidf_vectorizer is None or self.cosine_sim_matrix is None or self.indices is None:
            print("Model belum siap atau data artikel kosong, fallback ke populer.", file=sys.stderr)
            return self.get_popular_articles_details(num_recommendations)

        if article_id not in self.indices:
            print(f"Artikel ID {article_id} tidak ditemukan dalam indeks, fallback ke populer.", file=sys.stderr)
            return self.get_popular_articles_details(num_recommendations)

        idx = self.indices[article_id]
        if idx >= self.cosine_sim_matrix.shape[0]:
            print(f"Indeks {idx} di luar batas matriks kemiripan. Data mungkin tidak sinkron, fallback ke populer.", file=sys.stderr)
            return self.get_popular_articles_details(num_recommendations)

        sim_scores = list(enumerate(self.cosine_sim_matrix[idx]))
        sim_scores = sorted(sim_scores, key=lambda x: x[1], reverse=True)
        
        # Ambil artikel yang paling mirip, kecualikan artikel itu sendiri
        sim_scores = sim_scores[1:num_recommendations+1] # +1 karena kita kecualikan artikel asal

        article_indices = [i[0] for i in sim_scores]
        
        # Mengembalikan detail lengkap (id_artikel, judul_id, slug_id)
        recommended_articles_details = self.articles_df.iloc[article_indices][['id_artikel', 'judul_id', 'slug_id']].to_dict('records')
        return recommended_articles_details

    def get_popular_articles_details(self, num_recommendations=5):
        """
        Mengembalikan artikel populer sebagai fallback, berdasarkan skor popularitas gabungan
        dari views, scroll_percentage, dan time_spent_seconds.
        Mengembalikan daftar dictionary {'id_artikel', 'judul_id', 'slug_id'}.
        """
        if self.articles_df is None or self.articles_df.empty:
            print("Tidak ada data artikel untuk fallback populer.", file=sys.stderr)
            return []

        conn = None
        cursor = None
        try:
            conn = self._get_db_connection()
            cursor = conn.cursor(dictionary=True)

            # Mengambil artikel populer dengan skor gabungan
            cursor.execute("""
                SELECT
                    id_artikel,
                    SUM(CASE
                        WHEN activity_type = 'time_spent_seconds' THEN activity_value * 0.5
                        WHEN activity_type = 'scroll_percentage' THEN activity_value * 0.3
                        WHEN activity_type = 'view' THEN 0.2
                        ELSE 0
                    END) AS popularity_score
                FROM tb_user_activities
                WHERE id_artikel IS NOT NULL
                AND timestamp >= NOW() - INTERVAL 30 DAY
                GROUP BY id_artikel
                ORDER BY popularity_score DESC
                LIMIT %s
            """, (num_recommendations,))

            popular_articles_with_score = cursor.fetchall()

            if not popular_articles_with_score:
                print("Tidak ada data popularitas gabungan. Menggunakan fallback acak.", file=sys.stderr)
                return self.articles_df.sample(n=num_recommendations)[['id_artikel', 'judul_id', 'slug_id']].to_dict('records')

            popular_ids = [row['id_artikel'] for row in popular_articles_with_score]
            popular_df = self.articles_df[self.articles_df['id_artikel'].isin(popular_ids)]
            
            # Urutkan ulang sesuai urutan popularitas dari query
            popular_df['id_artikel'] = pd.Categorical(popular_df['id_artikel'], categories=popular_ids, ordered=True)
            popular_df = popular_df.sort_values('id_artikel')

            return popular_df[['id_artikel', 'judul_id', 'slug_id']].to_dict('records')

        except mysql.connector.Error as err:
            print(f"DB error in get_popular_articles_details: {err}", file=sys.stderr)
            # Fallback ke artikel acak jika ada error
            if not self.articles_df.empty:
                return self.articles_df.sample(n=num_recommendations)[['id_artikel', 'judul_id', 'slug_id']].to_dict('records')
            return []
        finally:
            if cursor: cursor.close()
            if conn: conn.close()

    # --- Metode BARU untuk Rekomendasi Berdasarkan Aktivitas User ---
    def get_recommendations_by_user_activity(self, session_id, num_recommendations=5, top_n_activity_articles=5):
        """
        Mengembalikan rekomendasi artikel berdasarkan aktivitas terbaru dari sebuah session_id,
        dengan mempertimbangkan bobot dari views, scroll_percentage, dan time_spent_seconds.
        Mengembalikan daftar dictionary {'id_artikel', 'judul_id', 'slug_id'}.
        """
        # Fallback jika model atau data belum siap
        if self.articles_df is None or self.articles_df.empty or \
        self.tfidf_vectorizer is None or self.cosine_sim_matrix is None or self.indices is None:
            print("Model atau data artikel belum siap, fallback ke populer.", file=sys.stderr)
            return self.get_popular_articles_details(num_recommendations)

        conn = None
        cursor = None
        try:
            conn = self._get_db_connection()
            cursor = conn.cursor(dictionary=True)

            # Mengambil artikel yang diinteraksi dengan bobot gabungan
            cursor.execute("""
                SELECT
                    id_artikel,
                    SUM(CASE
                        WHEN activity_type = 'time_spent_seconds' THEN activity_value * 0.6
                        WHEN activity_type = 'scroll_percentage' THEN activity_value * 0.3
                        WHEN activity_type = 'view' THEN 0.1
                        ELSE 0
                    END) AS combined_activity_score
                FROM tb_user_activities
                WHERE session_id = %s
                AND id_artikel IS NOT NULL
                AND timestamp >= NOW() - INTERVAL 30 DAY
                GROUP BY id_artikel
                ORDER BY combined_activity_score DESC
                LIMIT %s
            """, (session_id, top_n_activity_articles))
            
            recent_activity_articles = cursor.fetchall()
            
            if not recent_activity_articles:
                print(f"Tidak ada aktivitas terbaru untuk session_id: {session_id}. Menggunakan fallback populer.", file=sys.stderr)
                return self.get_popular_articles_details(num_recommendations)

            # Memfilter artikel yang tidak ada dalam indeks model
            activity_article_ids = [row['id_artikel'] for row in recent_activity_articles if row['id_artikel'] in self.indices]

            if not activity_article_ids:
                print(f"Artikel dari aktivitas {session_id} tidak ditemukan dalam indeks model. Menggunakan fallback populer.", file=sys.stderr)
                return self.get_popular_articles_details(num_recommendations)

            # Hitung kemiripan untuk setiap artikel dari aktivitas dan akumulasikan skornya
            all_similar_articles = {}
            for art_id in activity_article_ids:
                if art_id not in self.indices: continue
                
                idx = self.indices[art_id]
                if idx >= self.cosine_sim_matrix.shape[0]: continue

                sim_scores = list(enumerate(self.cosine_sim_matrix[idx]))
                
                for i, score in sim_scores:
                    current_article_id = self.articles_df.iloc[i]['id_artikel']
                    # Kecualikan artikel yang menjadi sumber rekomendasi
                    if current_article_id == art_id or current_article_id in activity_article_ids:
                        continue
                    
                    if current_article_id not in all_similar_articles:
                        all_similar_articles[current_article_id] = 0
                    all_similar_articles[current_article_id] += score

            if not all_similar_articles:
                print("Tidak ada artikel serupa yang ditemukan. Menggunakan fallback populer.", file=sys.stderr)
                return self.get_popular_articles_details(num_recommendations)

            # Urutkan berdasarkan total skor kemiripan
            sorted_similar_articles = sorted(all_similar_articles.items(), key=lambda item: item[1], reverse=True)

            # Ambil N rekomendasi teratas dan detailnya
            recommended_ids = [art_id for art_id, score in sorted_similar_articles[:num_recommendations]]
            recommended_df = self.articles_df[self.articles_df['id_artikel'].isin(recommended_ids)]
            
            # Urutkan ulang hasil DataFrame sesuai urutan skor
            recommended_df['id_artikel'] = pd.Categorical(recommended_df['id_artikel'], categories=recommended_ids, ordered=True)
            recommended_df = recommended_df.sort_values('id_artikel')

            return recommended_df[['id_artikel', 'judul_id', 'slug_id']].to_dict('records')

        except mysql.connector.Error as err:
            print(f"Error fetching user activity from DB: {err}", file=sys.stderr)
            return self.get_popular_articles_details(num_recommendations)
        except Exception as e:
            print(f"Error in get_recommendations_by_user_activity: {e}", file=sys.stderr)
            return self.get_popular_articles_details(num_recommendations)
        finally:
            if cursor: cursor.close()
            if conn: conn.close()

    def get_avg_read_time_articles(self, num_recommendations=5):
        """
        Mengambil artikel dengan rata-rata waktu baca tertinggi.
        Output: [{'id_artikel': ..., 'judul_id': ..., 'slug_id': ..., 'avg_time': ...}, ...]
        """
        conn = None
        cursor = None
        try:
            conn = self._get_db_connection()
            cursor = conn.cursor(dictionary=True)

            cursor.execute("""
                SELECT ua.id_artikel, 
                    AVG(ua.value) AS avg_time
                FROM tb_user_activities ua
                WHERE ua.activity_type = 'time_spent_seconds'
                GROUP BY ua.id_artikel
                ORDER BY avg_time DESC
                LIMIT %s
            """, (num_recommendations,))

            avg_articles = cursor.fetchall()
            if not avg_articles or self.articles_df is None or self.articles_df.empty:
                print("Tidak ada data rata-rata waktu baca.", file=sys.stderr)
                return []

            # Atur id_artikel sebagai indeks untuk pencarian cepat
            articles_indexed = self.articles_df.set_index('id_artikel')

            result = []
            for row in avg_articles:
                art_id = row['id_artikel']
                if art_id in articles_indexed.index:
                    detail_row = articles_indexed.loc[art_id]
                    result.append({
                        "id_artikel": detail_row['id_artikel'],
                        "judul_id": detail_row['judul_id'],
                        "slug_id": detail_row['slug_id'],
                        "avg_time": float(row['avg_time'])
                    })
            return result

        except mysql.connector.Error as err:
            print(f"DB error in get_avg_read_time_articles: {err}", file=sys.stderr)
            return []
        finally:
            if cursor: cursor.close()
            if conn: conn.close()('        if conn: conn.close()')
