from flask import Blueprint, request, jsonify
from flask_jwt_extended import jwt_required, get_jwt_identity, create_access_token
from extensions import db
from models.user import User

auth_bp = Blueprint("auth_bp", __name__)

@auth_bp.route("/test", methods=["GET"])
def test_auth():
    return {"message": "Auth blueprint çalışıyor!"}

@auth_bp.route("/register", methods=["POST"])
def register():
    data = request.get_json()

    username = data.get("username")
    email = data.get("email")
    password = data.get("password")

    if not username:
        return jsonify({"error": "Kullanıcı adı zorunludur."}), 400
    if not email:
        return jsonify({"error": "E-posta zorunludur."}), 400
    if not password:
        return jsonify({"error": "Şifre zorunludur."}), 400

    if User.query.filter_by(email=email).first():
        return jsonify({"error": "Bu e-posta zaten kayıtlı."}), 409

    new_user = User(username=username, email=email)
    new_user.set_password(password)

    db.session.add(new_user)
    db.session.commit()

    return jsonify({"message": "Kayıt başarılı!"}), 201

@auth_bp.route("/login", methods=["POST"])
def login():
    data = request.get_json()
    email = data.get("email")
    password = data.get("password")

    if not email:
        return jsonify({"error": "E-posta zorunludur."}), 400
    if not password:
        return jsonify({"error": "Şifre zorunludur."}), 400

    user = User.query.filter_by(email=email).first()

    if not user:
        return jsonify({"error": "Kullanıcı bulunamadı."}), 404

    if not user.check_password(password):
        return jsonify({"error": "Şifre yanlış."}), 401

    access_token = create_access_token(identity=str(user.id))
    return jsonify({
        "access_token": access_token,
        "username": user.username
    }), 200

@auth_bp.route("/user", methods=["GET"])
@jwt_required()
def get_user():
    user_id = get_jwt_identity()
    user = User.query.get(user_id)

    if not user:
        return jsonify({"error": "Kullanıcı bulunamadı"}), 404

    return jsonify({
        "id": user.id,
        "username": user.username,
        "email": user.email
    })

@auth_bp.route("/user", methods=["PUT"])
@jwt_required()
def update_user():
    user_id = get_jwt_identity()
    user = User.query.get(user_id)

    if not user:
        return jsonify({"error": "Kullanıcı bulunamadı"}), 404

    data = request.get_json()
    username = data.get("username")
    email = data.get("email")
    password = data.get("password")

    if username:
        user.username = username
    if email:
        user.email = email
    if password:
        user.set_password(password)

    db.session.commit()
    return jsonify({"message": "Kullanıcı bilgileri güncellendi"}), 200

@auth_bp.route("/user", methods=["DELETE"])
@jwt_required()
def delete_user():
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)

    if not user:
        return jsonify({"error": "Kullanıcı bulunamadı."}), 404

    db.session.delete(user)
    db.session.commit()
    return jsonify({"message": "Kullanıcı silindi."}), 200

@auth_bp.route("/all_users", methods=["GET"])
@jwt_required()
def get_all_users():
    current_user_id = get_jwt_identity()
    current_user = User.query.get(current_user_id)

    if not current_user or not current_user.is_admin:
        return jsonify({"error": "Yetkisiz erişim"}), 403

    users = User.query.all()
    result = []
    for user in users:
        result.append({
            "id": user.id,
            "username": user.username,
            "email": user.email
        })
    return jsonify(result), 200

@auth_bp.route("/user/<int:user_id>", methods=["DELETE"])
@jwt_required()
def delete_user_by_admin(user_id):
    current_user_id = get_jwt_identity()
    current_user = User.query.get(current_user_id)

    if not current_user or not current_user.is_admin:
        return jsonify({"error": "Yetkisiz erişim"}), 403

    user = User.query.get(user_id)
    if not user:
        return jsonify({"error": "Kullanıcı bulunamadı."}), 404

    db.session.delete(user)
    db.session.commit()
    return jsonify({"message": "Kullanıcı silindi."}), 200
