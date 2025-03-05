from flask import Flask, request
from datetime import date
import mysql.connector
import logging

app = Flask(__name__)
today = date.today()
db = mysql.connector.connect(
    host="localhost", user="root", password="", database="salologan"
)
cursor = db.cursor(dictionary=True)


@app.route("/setAppointment", methods=["GET"])
def setAppoinment():

    try:
        startAppointment = request.args.get("startAppointment")
        endAppointment = request.args.get("endAppointment")
        note = request.args.get("note")
        user_id = request.args.get("user_id")
        customerSelect = request.args.get("customerSelect")
        serviceType = request.args.get("serviceType")

        query = "INSERT INTO appointment (user_id, customer, service_type, appointment_start, appointment_end, note, status) VALUES (%s,%s,%s,%s,%s,%s,%s)"
        cursor.execute(
            query,
            (
                user_id,
                customerSelect,
                serviceType,
                startAppointment,
                endAppointment,
                note,
                1,
            ),
        )
        db.commit()
        return f"Appointment Scheduled"
    except Exception as e:
        print(e)


@app.route("/selectCustomer", methods=["GET"])
def selectCustomer():
    try:
        query = "SELECT * FROM customer_detail"
        cursor.execute(query)
        result = cursor.fetchall()
        return result
    except Exception as e:
        print(e)


@app.route("/selectUser", methods=["GET"])
def selectUser():
    try:
        role = request.args.get("role")
        query = "SELECT * FROM `user` WHERE role = %s"
        cursor.execute(query, (role,))
        result = cursor.fetchall()
        return result
    except Exception as e:
        print(e)


@app.route("/register", methods=["GET"])
def register():

    firstName = request.args.get("firstName")
    lastName = request.args.get("lastName")
    username = request.args.get("username")
    password = request.args.get("password")
    email = request.args.get("email")
    gender = request.args.get("gender")
    phoneNumber = request.args.get("phoneNumber")
    role = request.args.get("role")

    query = "INSERT INTO user (first_name, last_name, username, password, email, gender, phone, role) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)"

    cursor.execute(
        query,
        (firstName, lastName, username, password, email, gender, phoneNumber, role),
    )
    db.commit()

    return f"Registration complete"


@app.route("/login", methods=["GET"])
def login():
    username = request.args.get("username")

    query = """
    SELECT * FROM user WHERE username=%s
    """
    cursor.execute(query, (username,))
    result = cursor.fetchall()
    print(result)
    if result is None:
        return "User not found"

    return result


@app.route("/addCustomer", methods=["GET"])
def appoint():
    firstName = request.args.get("firstName")
    lastName = request.args.get("lastName")
    age = request.args.get("age")
    gender = request.args.get("gender")
    email = request.args.get("email")
    phoneNumber = request.args.get("phoneNumber")

    street = request.args.get("street")
    baranggay = request.args.get("baranggay")
    municipality = request.args.get("municipality")
    district = request.args.get("district")
    post_code = request.args.get("post_code")
    city = request.args.get("city")
    country = request.args.get("country")

    try:
        query = "INSERT INTO address (street, baranggay, municipality, district, post_code, city, country) VALUES (%s,%s,%s,%s,%s,%s,%s)"
        cursor.execute(
            query, (street, baranggay, municipality, district, post_code, city, country)
        )
        db.commit()
        address_id = cursor.lastrowid
        print(address_id)

        try:
            query = "INSERT INTO customer_detail (first_name, last_name, age, gender, email, phone_number, address) VALUES (%s,%s,%s,%s,%s,%s,%s)"
            cursor.execute(
                query,
                (firstName, lastName, age, gender, email, phoneNumber, address_id),
            )
            db.commit()

            return "Customer Added"

        except Exception as e:

            db.rollback()
            return f"Customer detail insert error occurred: {e}"

    except Exception as e:

        db.rollback()
        return f"Address insert error occurred: {e}"


if __name__ == "__main__":
    app.run(debug=True)
