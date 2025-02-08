from flask import Flask, request, jsonify
from datetime import date
import mysql.connector
import logging

app = Flask(__name__)
today = date.today()
db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="salologan"
)
cursor = db.cursor()

@app.route('/index')
def index():
    query = """
    SELECT 
    appointment.id AS appointment_id, 
    user.first_name AS user_first_name, 
    user.last_name AS user_last_name, 
    customer_detail.first_name AS customer_first_name, 
    customer_detail.last_name AS customer_last_name, 
    appoint_date AS appointment_date, 
    service_type AS appointment_service, 
    note AS appointment_note, 
    appointment_status.status_name AS status
    
    FROM appointment 
    JOIN user ON appointment.user_id = user.id 
    JOIN customer_detail ON appointment.customer = customer_detail.id 
    JOIN appointment_status ON appointment.status = appointment_status.id;
    """
    cursor.execute(query)
    result = cursor.fetchall()
    return result

@app.route('/register', methods=['GET'])
def register():
    
    firstName = request.args.get("firstName")
    lastName = request.args.get("lastName")
    password = request.args.get("password")
    email = request.args.get("email")
    gender = request.args.get("gender")
    phoneNumber = request.args.get("phoneNumber")
    role = request.args.get("role")
    
    
        
    query = "INSERT INTO user (first_name, last_name, password, email, gender, phone, role) VALUES (%s, %s, %s, %s, %s, %s, %s)"
    # Execute the query with data values
    cursor.execute(query, (firstName, lastName, password, email, gender, phoneNumber, role))
    db.commit()

    return (f"User regitration complete")

@app.route('/login', methods=['GET'])
def login():
    username = request.args.get("username")
    password = request.args.get("password")
    
    query = """
    SELECT * FROM user WHERE username=%s
    """
    cursor.execute(query, (username,))
    result = cursor.fetchone()
    print (result)
    if result is None:
        print("user")
        return "User not found"

    if result[4] != password:
        print("password")
        return "Incorrect password"

    result = {
        'id': result[0],
        'firstname': result[1],
        'lastname': result[2],
        'username': result[3],
        'password': result[4],
        'email': result[5],
        'gender': result[6],
        'phone': result[7],
        'role': result[8]
    }

    return result
            
@app.route('/appoint', methods=['GET'])
def appoint():
    firstName = request.args.get('firstName')
    lastName = request.args.get('lastName')
    age = request.args.get('age')
    gender = request.args.get('gender')
    email = request.args.get('email')
    phoneNumber = request.args.get('phoneNumber')
    
    street = request.args.get('street')
    barangay = request.args.get('barangay')
    municipality = request.args.get('municipality')
    district = request.args.get('district')
    post_code = request.args.get('post_code')
    city = request.args.get('city')
    country = request.args.get('country')
    
    appointmentDate = request.args.get('appointmentDate')
    serviceType = request.args.get('serviceType')
    note = request.args.get('note')
    status = request.args.get('status')
    user_id = request.args.get('user_id')
    
    
    try:
        query = "INSERT INTO address (street, barangay, municipality, district, post_code, city, country) VALUES (%s,%s,%s,%s,%s,%s,%s)"
        cursor.execute(query,(street, barangay, municipality, district, post_code, city, country))
        db.commit()
        address_id = cursor.lastrowid
        print(address_id)
        
        try:
            query = "INSERT INTO customer_detail (first_name, last_name, age, gender, email, phone_number, address) VALUES (%s,%s,%s,%s,%s,%s,%s)"
            cursor.execute(query,(firstName,lastName,age,gender,email,phoneNumber, address_id))
            db.commit()
            customer_id = cursor.lastrowid
            print(customer_id)
            
            try:
                query = "INSERT INTO appointment (user_id,customer,appoint_date,service_type,note,status) VALUES (%s,%s,%s,%s,%s,%s)"
                
                cursor.execute(query,(user_id, customer_id, appointmentDate, serviceType, note, status))
                db.commit()
                
                return (f"Appointment Successful")
                
            except Exception as e:
                
                db.rollback()
                return(f"Appoinment detail insert error occurred: {e}")
            
        except Exception as e:
            
            db.rollback()
            return(f"Customer detail insert error occurred: {e}")
        
    except Exception as e:
        
        db.rollback()
        return (f"Address insert error occurred: {e}")
    
if __name__ == "__main__":
    app.run(debug=True)