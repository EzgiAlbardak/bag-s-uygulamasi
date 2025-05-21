from extensions import db
from models.user import User
from app import app

with app.app_context():
    existing_admin = User.query.filter_by(email="admin@admin.com").first()
    
    if existing_admin:
        print("⚠️ Admin zaten kayıtlı. Şifresi güncelleniyor...")
        existing_admin.set_password("123")  # ✅ Yeni şifreyi buraya yazabilirsiniz github kullanıcısı
        db.session.commit()
        print("✅ Şifre başarıyla güncellendi!")
    else:
        admin = User(username="adminn", email="admin@admin.com")
        admin.set_password("123")  # ✅ Yeni şifre buradadır.
        admin.is_admin = True
        db.session.add(admin)
        db.session.commit()
        print("✅ Admin başarıyla oluşturuldu!")
