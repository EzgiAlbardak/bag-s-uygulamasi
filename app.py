from flask import Flask
from flask_cors import CORS
from config import Config
from extensions import db, jwt
from routes.auth_routes import auth_bp
from routes.donation_routes import donation_bp

app = Flask(__name__)
app.config.from_object(Config)

CORS(app,
     resources={r"/*": {"origins": "*"}},
     supports_credentials=True,
     expose_headers=["Authorization"],
     allow_headers=["Content-Type", "Authorization"]
)

db.init_app(app)
jwt.init_app(app)

app.register_blueprint(auth_bp, url_prefix="/api/auth")
app.register_blueprint(donation_bp, url_prefix="/api/donations")

@app.route("/")
def home():
    return {"message": "Bağış API çalışıyor"}

if __name__ == "__main__":
    with app.app_context():
        from models.user import User
        from models.donation import Donation
        db.create_all()

    app.run(debug=True)
