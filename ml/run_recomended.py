import sys, json
from ml_core import MLRecommender

if __name__ == "__main__":
    session_id = sys.argv[1]
    num_recs = int(sys.argv[2])

    recommender = MLRecommender()
    result = recommender.get_recommendations_by_user_activity(
        session_id, num_recommendations=num_recs
    )
    print(json.dumps(result))
