from flask import Blueprint, request, jsonify
from flask_jwt_extended import jwt_required, get_jwt_identity
from extensions import db
from models.donation import Donation
from models.user import User  
from datetime import datetime

donation_bp = Blueprint('donation_bp', __name__)

@donation_bp.route("/", methods=["POST"])
@jwt_required()
def create_donation():
    data = request.get_json()
    amount = data.get("amount")
    description = data.get("description")
    user_id = get_jwt_identity()

    if not amount or not description:
        return jsonify({"error": "Tutar ve açıklama zorunludur."}), 400

    donation = Donation(amount=amount, description=description, user_id=user_id)
    db.session.add(donation)
    db.session.commit()

    return jsonify({"message": "Bağış eklendi!"}), 201

@donation_bp.route("/", methods=["GET"])
@jwt_required()
def get_donations():
    user_id = get_jwt_identity()
    min_amount = request.args.get("min_amount", type=float)
    start_date_str = request.args.get("start_date")

    query = Donation.query.filter_by(user_id=user_id)

    if min_amount:
        query = query.filter(Donation.amount >= min_amount)

    if start_date_str:
        try:
            start_date = datetime.strptime(start_date_str, "%Y-%m-%d")
            query = query.filter(Donation.created_at >= start_date)
        except ValueError:
            return jsonify({"error": "start_date formatı YIL-AY-GÜN olmalı (örn: 2024-05-01)"}), 400

    donations = query.all()
    result = [{
        "id": d.id,
        "amount": d.amount,
        "description": d.description,
        "created_at": d.created_at.strftime("%Y-%m-%d %H:%M:%S")
    } for d in donations]

    return jsonify(result), 200

@donation_bp.route("/all", methods=["GET"])
@jwt_required()
def list_user_donations():
    user_id = get_jwt_identity()
    donations = Donation.query.filter_by(user_id=user_id).all()

    result = [{
        "id": d.id,
        "amount": d.amount,
        "description": d.description,
        "created_at": d.created_at.strftime("%Y-%m-%d %H:%M:%S")
    } for d in donations]

    return jsonify(result), 200

from models.user import User 

@donation_bp.route("/all_admin", methods=["GET"])
@jwt_required()
def get_all_donations_admin():
    query = request.args.get("query", "")
    page = int(request.args.get("page", 1))
    per_page = 10 

    donation_query = Donation.query.join(User).add_columns(User.username, User.email)

    if query:
        donation_query = donation_query.filter(
            (User.username.ilike(f"%{query}%")) |
            (User.email.ilike(f"%{query}%"))
        )

    paginated = donation_query.paginate(page=page, per_page=per_page, error_out=False)

    results = []
    for d, username, email in paginated.items:
        results.append({
            "id": d.id,
            "amount": d.amount,
            "description": d.description,
            "created_at": d.created_at.strftime("%Y-%m-%d %H:%M:%S"),
            "username": username,
            "email": email
        })

    return jsonify({
        "donations": results,
        "total": paginated.total,
        "pages": paginated.pages,
        "current_page": paginated.page
    }), 200


@donation_bp.route("/<int:donation_id>", methods=["DELETE"])
@jwt_required()
def delete_donation(donation_id):
    user_id = get_jwt_identity()
    donation = Donation.query.filter_by(id=donation_id, user_id=user_id).first()

    if not donation:
        return jsonify({"error": "Bağış bulunamadı veya yetkiniz yok."}), 404

    db.session.delete(donation)
    db.session.commit()

    return jsonify({"message": "Bağış silindi."}), 200
