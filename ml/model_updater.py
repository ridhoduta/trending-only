# C:\Ridho\Projek\Meachine Learning\recommender_api\model_updater.py

from ml_core import MLRecommender

if __name__ == "__main__":
    print("Memulai proses pembaruan model ML...")
    recommender = MLRecommender()
    recommender.fetch_articles_data()
    recommender.train_and_save_models()
    print("Pembaruan model ML selesai.")