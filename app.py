from flask import Flask, request
from datetime import date, datetime, timedelta
import mysql.connector
import logging
import hashlib
import json

app = Flask(__name__)
today = date.today()
db = mysql.connector.connect(
    host="localhost", user="root", password="", database="salologan"
)
cursor = db.cursor(dictionary=True)

@app.route("/changeStatus", methods=["GET"])
def changeStatus():
    user_id = request.args.get("user_id")
    status = request.args.get("status")

    query = "UPDATE appointment_backup SET status = %s WHERE user_id = %s"
    cursor.execute(query, (status, user_id))
    result = cursor.rowcount
    db.commit()
    return "success"

@app.route("/changeStatusaid", methods=["GET"])
def changeStatusaid():
    aid = request.args.get("aid")

    query = "UPDATE appointment_backup SET status = 6 WHERE aid = %s"
    cursor.execute(query, (aid,))
    result = cursor.rowcount
    db.commit()

    return "success"

@app.route("/isAppointed", methods=["GET"])
def isAppointed():
    user_id = request.args.get("user_id")

    query = "SELECT * FROM appointment_backup WHERE user_id = %s AND (status = 1 OR status = 2)"
    cursor.execute(query, (user_id,))
    result = cursor.fetchall()

    return result

@app.route("/getHistory", methods=["GET"])
def getHistory():
    query = """SELECT
        cd.firstName AS firstname,
        cd.lastName AS lastname,
        cd.user as user_id,
        ab.appointment_start,
        ab.appointment_end,
        ab.aid,
        ab.note,
        ab.status,
        ab.date
        FROM appointment_backup ab
        JOIN customer_detail cd ON ab.user_id = cd.user
        """
    cursor.execute(query)
    result = cursor.fetchall()

    if result:
        return result
    else:
        return "false"

@app.route("/finishAppointment", methods=["GET"])
def finishAppointment():
    id = request.args.get("id")
    status = request.args.get("status")

    query = "UPDATE `appointment_backup` SET status = %s WHERE aid = %s "
    cursor.execute(query, (status, id))
    db.commit()
    return "success"

@app.route("/getCustomerServices", methods=["GET"])
def getCustomerServices():
    appointment_id = request.args.get("appointment_id")
    query = """
    SELECT 
        st.service_type AS serviceType,
        st.price AS servicePrice
    FROM service s
    JOIN service_type st ON s.service_type = st.id
    WHERE s.appointment_id = %s
    """
    cursor.execute(query, (appointment_id,))
    result = cursor.fetchall()
    return result


@app.route("/additionalServices", methods=["GET"])
def additionalServices():
    additionalServices = request.args.get("additionalServices")
    appointment_id = request.args.get("appointment_id")
    user_id = request.args.get("user_id")

    query = "insert into service (user_id, appointment_id, service_type) values (%s, %s, %s)"
    cursor.execute(query, (user_id, appointment_id, additionalServices))
    db.commit()

    return "Success"

@app.route("/getServices", methods=["GET"])
def getServices():
    query = "select * from service_type"
    cursor.execute(query)
    result = cursor.fetchall()
    return result

@app.route("/setFindings", methods=["GET"])
def setFindings():
    user_id = request.args.get("user_id")


    tooth1 = request.args.get("tooth1")
    tooth2 = request.args.get("tooth2")
    tooth3 = request.args.get("tooth3")
    tooth4 = request.args.get("tooth4")
    tooth5 = request.args.get("tooth5")
    tooth6 = request.args.get("tooth6")
    tooth7 = request.args.get("tooth7")
    tooth8 = request.args.get("tooth8")
    tooth9 = request.args.get("tooth9")
    tooth10 = request.args.get("tooth10")
    tooth11 = request.args.get("tooth11")
    tooth12 = request.args.get("tooth12")
    tooth13 = request.args.get("tooth13")
    tooth14 = request.args.get("tooth14")
    tooth15 = request.args.get("tooth15")
    tooth16 = request.args.get("tooth16")
    tooth17 = request.args.get("tooth17")
    tooth18 = request.args.get("tooth18")
    tooth19 = request.args.get("tooth19")
    tooth20 = request.args.get("tooth20")
    tooth21 = request.args.get("tooth21")
    tooth22 = request.args.get("tooth22")
    tooth23 = request.args.get("tooth23")
    tooth24 = request.args.get("tooth24")
    tooth25 = request.args.get("tooth25")
    tooth26 = request.args.get("tooth26")
    tooth27 = request.args.get("tooth27")
    tooth28 = request.args.get("tooth28")
    tooth29 = request.args.get("tooth29")
    tooth30 = request.args.get("tooth30")
    tooth31 = request.args.get("tooth31")
    tooth32 = request.args.get("tooth32")

    check_query = "SELECT id FROM tooth_conditions WHERE user_id = %s"
    cursor.execute(check_query, (user_id,))
    existing = cursor.fetchone()

    if existing:
        update_query = """
        UPDATE tooth_conditions SET
            tooth1 = %s, tooth2 = %s, tooth3 = %s, tooth4 = %s, tooth5 = %s, tooth6 = %s, tooth7 = %s, tooth8 = %s,
            tooth9 = %s, tooth10 = %s, tooth11 = %s, tooth12 = %s, tooth13 = %s, tooth14 = %s, tooth15 = %s, tooth16 = %s,
            tooth17 = %s, tooth18 = %s, tooth19 = %s, tooth20 = %s, tooth21 = %s, tooth22 = %s, tooth23 = %s, tooth24 = %s,
            tooth25 = %s, tooth26 = %s, tooth27 = %s, tooth28 = %s, tooth29 = %s, tooth30 = %s, tooth31 = %s, tooth32 = %s
            WHERE user_id = %s
            """

        cursor.execute(update_query, (
            tooth1, tooth2, tooth3, tooth4, tooth5, tooth6, tooth7, tooth8,
            tooth9, tooth10, tooth11, tooth12, tooth13, tooth14, tooth15, tooth16,
            tooth17, tooth18, tooth19, tooth20, tooth21, tooth22, tooth23, tooth24,
            tooth25, tooth26, tooth27, tooth28, tooth29, tooth30, tooth31, tooth32,
            user_id
        ))

        db.commit()
        return {"message": "Findings updated."}
    else:
   
        insert_query = """
            INSERT INTO tooth_conditions (
                user_id,
                tooth1, tooth2, tooth3, tooth4, tooth5, tooth6, tooth7, tooth8,
                tooth9, tooth10, tooth11, tooth12, tooth13, tooth14, tooth15, tooth16,
                tooth17, tooth18, tooth19, tooth20, tooth21, tooth22, tooth23, tooth24,
                tooth25, tooth26, tooth27, tooth28, tooth29, tooth30, tooth31, tooth32
            ) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s,
                    %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
        """

        cursor.execute(insert_query, (
            user_id,
            tooth1, tooth2, tooth3, tooth4, tooth5, tooth6, tooth7, tooth8,
            tooth9, tooth10, tooth11, tooth12, tooth13, tooth14, tooth15, tooth16,
            tooth17, tooth18, tooth19, tooth20, tooth21, tooth22, tooth23, tooth24,
            tooth25, tooth26, tooth27, tooth28, tooth29, tooth30, tooth31, tooth32
        ))
        db.commit()
        inserted_id = cursor.lastrowid

        return {"message": "Findings inserted.", "inserted_id": inserted_id}


@app.route("/getAppointedCustomer", methods=["GET"])
def getAppointedCustomer():
    aid = request.args.get("aid")

    query = """
    SELECT
    ab.status as status,
    cd.user AS user_id,
    cd.firstName AS firstname,
    cd.lastName AS lastname,
    cd.nickName AS nickname,
    cd.address AS address,
    cd.contactNumber AS contactNumber,
    cd.facebook AS facebook,
    cd.birthDay AS birthDay,
    cd.nationality AS nationality,
    cd.age AS age,
    cd.gender AS gender,
    cd.civilStatus AS civilStatus,
    cd.occupation AS occupation,
    cd.employer AS employer,
    cd.clinic AS clinic,
    cd.prevClinic AS prevClinic,
    cd.emergencyFirstname AS emergencyFirstname,
    cd.emergencyLastname AS emergencyLastname,
    cd.relationship AS relationship,
    cd.emergencyContactNumber AS emergencyContactNumber,

    u.email AS email,
    st.service_type as serviceType,
    st.price as servicePrice,
    

    tc.tooth1 AS tooth1,
    tc.tooth2 AS tooth2,
    tc.tooth3 AS tooth3,
    tc.tooth4 AS tooth4,
    tc.tooth5 AS tooth5,
    tc.tooth6 AS tooth6,
    tc.tooth7 AS tooth7,
    tc.tooth8 AS tooth8,
    tc.tooth9 AS tooth9,
    tc.tooth10 AS tooth10,
    tc.tooth11 AS tooth11,
    tc.tooth12 AS tooth12,
    tc.tooth13 AS tooth13,
    tc.tooth14 AS tooth14,  
    tc.tooth15 AS tooth15,
    tc.tooth16 AS tooth16,
    tc.tooth17 AS tooth17,
    tc.tooth18 AS tooth18,
    tc.tooth19 AS tooth19,
    tc.tooth20 AS tooth20,
    tc.tooth21 AS tooth21,
    tc.tooth22 AS tooth22,
    tc.tooth23 AS tooth23,
    tc.tooth24 AS tooth24,  
    tc.tooth25 AS tooth25,
    tc.tooth26 AS tooth26,
    tc.tooth27 AS tooth27,
    tc.tooth28 AS tooth28,
    tc.tooth29 AS tooth29,
    tc.tooth30 AS tooth30,
    tc.tooth31 AS tooth31,
    tc.tooth32 AS tooth32

    FROM appointment_backup ab
    join customer_detail cd ON ab.user_id = cd.user
    join tooth_conditions tc ON ab.user_id = tc.user_id
    join service s on ab.user_id = s.user_id
    join service_type st on s.service_type = st.id
    join user u ON ab.user_id = u.id
    WHERE ab.aid = %s
    """
    
    cursor.execute(query, (aid,))
    result = cursor.fetchall()
    return result


@app.route("/getAppointment", methods=["GET"])
def getAppointment():

    date = request.args.get("date")

    query = "select * from appointment_backup where `date` = %s"
    cursor.execute(query, (date,))
    result = cursor.fetchall()
    
    return result

@app.route("/setAppointment", methods=["GET"])
def setAppoinment():
    try:
        
        user_id = request.args.get("user_id")
        startAppointment = request.args.get("startAppointment")
        endAppointment = request.args.get("endAppointment")
        note = request.args.get("note")
        serviceType = request.args.get("serviceType")
        date = request.args.get("date")
        status = request.args.get("status")

        query = "INSERT INTO appointment_backup (user_id , appointment_start, appointment_end, note, status, date) VALUES (%s,%s,%s,%s,%s,%s)"
        cursor.execute( query, ( user_id, startAppointment,endAppointment,note,status,date ))
        db.commit()
        inserted_id = cursor.lastrowid



        if inserted_id:
            query = "insert into service (user_id, appointment_id, service_type) values (%s, %s, %s)"
            cursor.execute(query, (user_id, inserted_id, serviceType))
            db.commit()
            
        
        hStartAppointment = hashlib.md5(startAppointment.encode()).hexdigest()
        hEndAppointment = hashlib.md5(endAppointment.encode()).hexdigest()
        hServiceType = hashlib.md5(serviceType.encode()).hexdigest()
        hNote = hashlib.md5(note.encode()).hexdigest()
        hdate = hashlib.md5(date.encode()).hexdigest()

        query = "INSERT INTO appointment (user_id , appointment_start, appointment_end, service_type, note, status, date) VALUES (%s,%s,%s,%s,%s,%s,%s)"
        cursor.execute(query, (user_id,hStartAppointment,hEndAppointment,hServiceType,hNote,1,hdate))
        db.commit()

        query = "SELECT * FROM appointment_backup WHERE aid = %s"
        cursor.execute(query,(inserted_id,))
        result = cursor.fetchall()
        
        return result
    except Exception as e:
        return 

@app.route("/selectCustomer", methods=["GET"])
def selectCustomer():
    try:
        query = "SELECT * FROM customer_detail"
        cursor.execute(query)
        result = cursor.fetchall()
        return result
    except Exception as e:
        return e

@app.route("/selectUser", methods=["GET"])
def selectUser():
    try:
        role = request.args.get("role")
        query_user = "SELECT * FROM user WHERE role = %s"
        cursor.execute(query_user, (role,))
        users = cursor.fetchall()

        user_details = []
        for user in users:
            user_id = user['id']
            
            
            cursor.execute("SELECT * FROM customer_detail WHERE `user` = %s", (user_id,))
            customer_detail = cursor.fetchall()
            
            
            cursor.execute("SELECT * FROM medication WHERE `user` = %s", (user_id,))
            medication = cursor.fetchall()
            
            
            cursor.execute("SELECT * FROM taken WHERE `user` = %s", (user_id,))
            taken = cursor.fetchall()
            
            user_details.append({
            "user": user,
            "customer_detail": customer_detail,
            "medication": medication,
            "taken": taken
            })
            
        return user_details

    except Exception as e:
        return {"error": str(e)}

@app.route("/register", methods=["GET"])
def register():

    username = request.args.get("username")
    password = request.args.get("password")
    email = request.args.get("email")
    role = request.args.get("role")

    query = "INSERT INTO user (username, password , role, email) VALUES (%s, %s, %s , %s)"
    cursor.execute(query, (username, password, role, email))
    db.commit()
    inserted_id = cursor.lastrowid

    tooth_condition_query = "INSERT INTO tooth_conditions (user_id) VALUES (%s)"
    cursor.execute(tooth_condition_query, (inserted_id,))
    db.commit()
    tooth_id = cursor.lastrowid

    return {"message": "Registration complete", "inserted_id": inserted_id}

@app.route("/checkEmail", methods=["GET"])
def checkEmail():
    email = request.args.get("email")
    query = """ 
    select * from user where email = %s"""    
    cursor.execute(query, (email,))
    result = cursor.fetchall()
    return result

@app.route("/login", methods=["GET"])
def login():
    username = request.args.get("username")

    query = """
    SELECT * FROM user WHERE username=%s
    """
    cursor.execute(query, (username,))
    result = cursor.fetchall()
    
    if result is None:
        return "User not found"

    return result


@app.route("/addCustomer", methods=["GET"])
def appoint():

    id = request.args.get("id")
    firstName = request.args.get("firstName")
    middleName = request.args.get("middleName")
    lastName = request.args.get("lastName")
    nickName = request.args.get("nickName")
    address = request.args.get("address")
    contactNumber = request.args.get("contactNumber")
    facebook = request.args.get("facebook")
    email = request.args.get("email")
    birthDay = request.args.get("birthDay")
    nationality = request.args.get("nationality")
    age = request.args.get("age")
    gender = request.args.get("gender")
    civilStatus = request.args.get("civilStatus")
    occupation = request.args.get("occupation")
    employer = request.args.get("employer")
    clinic = request.args.get("clinic")
    prevClinic = request.args.get("prevClinic")
    emergencyFirstname = request.args.get("emergencyFirstname")
    emergencyLastname = request.args.get("emergencyLastname")
    relationship = request.args.get("relationship")
    emergencyContactNumber = request.args.get("emergencyContactNumber")

    conditions = request.args.get("conditions")
    conditions = json.loads(conditions)

    taken = request.args.get("taken")
    taken = json.loads(taken)

    try:
        query = """
            INSERT INTO customer_detail (
                `user`, `firstName`, `middleName`, `lastName`, `nickName`, `address`,
                `contactNumber`, `facebook`, `email`, `birthDay`, `nationality`, `age`,
                `gender`, `civilStatus`, `occupation`, `employer`, `clinic`, `prevClinic`,
                `emergencyFirstname`, `emergencyLastname`, `relationship`, `emergencyContactNumber`
            ) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
        """
        cursor.execute(query, (
            id, firstName, middleName, lastName, nickName, address,
            contactNumber, facebook, email, birthDay, nationality, age,
            gender, civilStatus, occupation, employer, clinic, prevClinic,
            emergencyFirstname, emergencyLastname, relationship, emergencyContactNumber
        ))
        if taken:
            for item in taken:
                cursor.execute("INSERT INTO taken (`user`, `taken`) VALUES (%s, %s)", (id, item))
        if conditions:
            for condition in conditions:
                cursor.execute("INSERT INTO medication (`user`, `meds`) VALUES (%s, %s)", (id, condition))

        db.commit()
        return "Details inserted"

    except Exception as e:
        db.rollback()
        return f"Details insert error occurred: {e}"


if __name__ == "__main__":
    app.run(debug=True)
