import sys
import json

# Output pesan yang jelas ke stdout
sys.stdout.write(json.dumps({"status": "success", "message": "Python script is running and produced output."}))
sys.stdout.flush()