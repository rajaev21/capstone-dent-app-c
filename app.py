from flask import Flask, request, jsonify
from datetime import date, datetime
from flask_cors import CORS
import mysql.connector
import logging
import hashlib
import json
import base64
import os

app = Flask(__name__)
CORS(app)
today = date.today()
db_config = {
    "host": "localhost",
    "user": "root",
    "password": "",
    "database": "dentapp",
}


# @app.route("/getWaiting", methods=["GET"])
# def getWaiting():
#     conn = mysql.connector.connect(**db_config)
#     cursor = conn.cursor(dictionary=True)

#     date = request.args.get("date")
#     start = request.args.get("start")

#     query = """SELECT distinct
#             ab.aid as aid,
#             ab.user_id as user_id,
#             concat(cd.firstName, ' ' , cd.lastName) as name,
#             cd.age as age,
#             cd.gender as gender,
#             cd.contactNumber as number,
#             cd.address as address,
#             n.reason as reason,
#             n.message as message

#             FROM appointment_backup ab
#             join customer_detail cd on cd.user = ab.user_id
#             join notification n on n.aid = ab.aid
#             WHERE ab.date = %s
#             AND ab.appointment_start = %s
#             AND ab.status IN (1, 6)
#             """
#     cursor.execute(query, (date, start))
#     result = cursor.fetchall()

#     cursor.close()
#     conn.close()

#     return result


@app.route("/setSignature", methods=["POST"])
def setSignature():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
    data = request.get_json()
    signature = data.get("signature")
    user_id = data.get("user_id")

    if not signature or not user_id:
        return jsonify({"success": False, "message": "Missing data"}), 400

    signature = signature.replace("data:image/png;base64,", "")

    img_data = base64.b64decode(signature)
    folder = "signatures"
    os.makedirs(folder, exist_ok=True)
    file_path = os.path.join(folder, f"{user_id}.png")

    with open(file_path, "wb") as f:
        f.write(img_data)

    query = "UPDATE customer_detail SET signature = %s WHERE user = %s"
    cursor.execute(
        query,
        (
            file_path,
            user_id,
        ),
    )
    conn.commit()
    cursor.close()
    conn.close()

    return jsonify({"success": True, "file_path": file_path})


# @app.route("/changeStatusaid", methods=["GET"])
# def changeStatusaid():
#     conn = mysql.connector.connect(**db_config)
#     cursor = conn.cursor(dictionary=True)
#     aid = request.args.get("aid")

#     query = "UPDATE appointment_backup SET status = 6 WHERE aid = %s"
#     cursor.execute(query, (aid,))
#     result = cursor.rowcount
#     conn.commit()
#     cursor.close()
#     conn.close()

#     return {"message": "success"}


@app.route("/cancelBooked", methods=["GET"])
def cancelBooked():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
    aid = request.args.get("aid")
    reason = request.args.get("reason")
    user_id = request.args.get("user_id")

    try:
        conn.start_transaction()

        cursor.execute("select * from appointment_backup where aid = %s", (aid,))
        result = cursor.fetchall()
        notifyAdminsQuery = "insert into notification ( `aid`, `message`, `reason`, `sentBy`, `sentTo` ) select %s, %s, %s, %s, id from user where role = 'admin'"
        cursor.execute(
            "update appointment_backup set status = 4 where user_id = %s and status in (1,7,6,8,9,10)",
            (user_id,),
        )

        if result[0]["status"] == "2":
            cursor.execute(
                notifyAdminsQuery,
                (
                    aid,
                    "Appointment Cancel Request",
                    reason,
                    user_id,
                ),
            )
            cursor.execute(
                "update appointment_backup set status = 9 where aid = %s", (aid,)
            )

        conn.commit()
    except Exception as e:
        conn.rollback()
        print(e)
        return {"error": str(e)}, 500

    finally:
        cursor.close()
        conn.close()
        return {"response": "message"}


# @app.route("/rCancelApproval", methods=["GET"])
# def rCancelApproval():
#     conn = mysql.connector.connect(**db_config)
#     cursor = conn.cursor(dictionary=True)
#     aid = request.args.get("aid")
#     admin_id = request.args.get("admin_id")
#     user_id = request.args.get("user_id")
#     answer = request.args.get("answer")

#     if answer == "Yes":

#         result = cursor.rowcount

#         notificationQuery = "insert into notification ( `aid`, `message`, `sentBy`, `sentTo`) values (%s, %s, %s, %s)"
#         cursor.execute(
#             notificationQuery, (aid, "Cancellation Request Approved", admin_id, user_id)
#         )
#         conn.commit()
#     else:
#         query = "UPDATE appointment_backup SET status = 2 WHERE aid = %s"
#         cursor.execute(query, (aid,))
#         result = cursor.rowcount

#         notificationQuery = "insert into notification ( `aid`, `message`, `sentBy`, `sentTo`) values (%s, %s, %s, %s)"
#         cursor.execute(
#             notificationQuery, (aid, "Cancellation Request Denied", admin_id, user_id)
#         )
#         conn.commit()

#     cursor.close()
#     conn.close()
#     return "success"


# @app.route("/changeStatus", methods=["GET"])
# def changeStatus():
#     conn = mysql.connector.connect(**db_config)
#     cursor = conn.cursor(dictionary=True)
#     aid = request.args.get("aid")
#     query = "UPDATE appointment_backup SET status = 5 WHERE aid = %s"
#     cursor.execute(query, (aid,))
#     result = cursor.rowcount
#     conn.commit()

#     cursor.close()
#     conn.close()
#     return "success"


@app.route("/getNextAppointment", methods=["GET"])
def getNextAppointment():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)

    date = request.args.get("date")
    end = request.args.get("end")
    start = request.args.get("start")

    query = "SELECT *  FROM appointment_backup WHERE date = %s AND status = 2 AND ( appointment_start < %s AND appointment_end > %s )"
    cursor.execute(query, (date, end, start))
    result = cursor.fetchall()

    cursor.close()
    conn.close()
    return result


@app.route("/isAppointed", methods=["GET"])
def isAppointed():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
    user_id = request.args.get("user_id")
    query = "SELECT * FROM appointment_backup ab join customer_detail cd on cd.user = ab.user_id WHERE user_id = %s AND status not in (4,5,3,8) order by aid asc limit 1"
    cursor.execute(query, (user_id,))
    result = cursor.fetchall()

    cursor.close()
    conn.close()
    return result


@app.route("/getCustomerDetails", methods=["GET"])
def getCustomerDetails():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
    user_id = request.args.get("user_id")
    query = "select isValidated from customer_detail where user = %s"
    cursor.execute(query, (user_id,))
    result = cursor.fetchall()

    cursor.close()
    conn.close()
    return result


@app.route("/getCustomerProfile", methods=["GET"])
def getCustomerProfile():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
    user_id = request.args.get("id")
    query = """
        SELECT 
        cd.*, 
        u.username, 
        GROUP_CONCAT(DISTINCT m.meds) AS medications,
        GROUP_CONCAT(DISTINCT t.taken) AS taken_list

        FROM customer_detail cd
        INNER JOIN user u ON u.id = cd.user
        LEFT JOIN medication m ON m.user = cd.user
        LEFT JOIN taken t ON t.user = cd.user
        WHERE cd.user = %s
        GROUP BY cd.user;
        """
    cursor.execute(query, (user_id,))
    result = cursor.fetchall()
    cursor.close()
    conn.close()
    return result


@app.route("/getNotification", methods=["GET"])
def getNotification():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)

    user_id = request.args.get("user_id")

    query = "SELECT *, n.reason as reason FROM notification n join appointment_backup ab on ab.aid = n.aid WHERE sentTo = %s ORDER BY n.created_at DESC limit 7"
    cursor.execute(query, (user_id,))
    result = cursor.fetchall()

    cursor.close()
    conn.close()

    return result


@app.route("/getHistory", methods=["GET"])
def getHistory():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
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

    cursor.close()
    conn.close()
    if result:
        return result
    else:
        return "false"


@app.route("/finishAppointment", methods=["GET"])
def finishAppointment():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)

    id = request.args.get("id")
    status = request.args.get("status")
    admin_id = request.args.get("admin_id")
    user_id = request.args.get("user_id")
    reason = request.args.get("reason") or ""

    if status == "4":
        message = "Appointment Cancelled"

    if status == "3":
        message = "Appointment Done"

    if status == "2":
        message = "Appointment Approved"
        cursor.execute(
            "update appointment_backup set status = 10 where user_id = %s and status = 3",
            (user_id,),
        )
        cursor.execute(
            "update appointment_backup set status = 4 where user_id = %s and status = 2",
            (user_id,),
        )
        conn.commit()

    query = "UPDATE `appointment_backup` SET status = %s WHERE aid = %s "
    cursor.execute(query, (status, id))
    conn.commit()
    affected = cursor.rowcount

    newQuery = """
    INSERT INTO `notification` (`aid`, `message`, `reason`, `sentBy`, `sentTo`) 
    VALUES (%s, %s, %s, %s, %s)
    """
    cursor.execute(newQuery, (id, message, reason, admin_id, user_id))
    conn.commit()
    notif = cursor.lastrowid

    cursor.close()
    conn.close()
    return {"notify": notif}


@app.route("/cancelAppointments", methods=["GET"])
def cancelAppointments():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
    date = request.args.get("date")
    start = request.args.get("start")
    admin_id = request.args.get("admin_id")

    selectQuery = """
        SELECT aid, user_id 
        FROM appointment_backup 
        WHERE status in (1,6) AND date = %s AND appointment_start = %s
    """
    cursor.execute(selectQuery, (date, start))
    result = cursor.fetchall()

    for row in result:
        user_id = row["user_id"]
        aid = row["aid"]

        updateQuery = """
            UPDATE appointment_backup 
            SET status = 4 
            WHERE aid = %s AND date = %s AND appointment_start = %s
        """
        cursor.execute(updateQuery, (aid, date, start))
        conn.commit()
        newQuery = """
            INSERT INTO `notification` (`aid` ,`message`, `sentBy`, `sentTo`) 
            VALUES (%s, %s, %s, %s)
        """
        cursor.execute(
            newQuery, (aid, "Another user has been appointed", admin_id, user_id)
        )
        conn.commit()

    cursor.close()
    conn.close()
    return "success"


@app.route("/getCustomerServices", methods=["GET"])
def getCustomerServices():

    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)

    appointment_id = request.args.get("appointment_id")
    query = """
        SELECT DISTINCT
        st.service_type AS serviceType,
        st.price AS servicePrice
        FROM service s
        JOIN service_type st ON s.service_type = st.id
        WHERE s.appointment_id = %s
        """
    cursor.execute(query, (appointment_id,))
    result = cursor.fetchall()

    cursor.close()
    conn.close()
    return result


@app.route("/getDentist", methods=["GET"])
def getDentist():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)

    aid = request.args.get("aid")
    query = "select * from dentist where aid = %s"
    cursor.execute(query, (aid,))
    result = cursor.fetchall()

    cursor.close()
    conn.close()
    return result


@app.route("/getPayments", methods=["GET"])
def getPayments():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)

    aid = request.args.get("aid")
    query = "select * from payment where aid = %s"
    cursor.execute(query, (aid,))
    result = cursor.fetchall()

    cursor.close()
    conn.close()
    return result


@app.route("/addPayment", methods=["POST"])
def addPayment():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
    data = request.get_json()
    payment = data.get("payment")
    aid = data.get("aid")
    query = "insert into payment ( payment, aid ) values ( %s, %s )"
    cursor.execute(query, (payment, aid))

    conn.commit()
    cursor.close()
    conn.close()
    return {"message": "Success"}


@app.route("/addDentist", methods=["POST"])
def addDentist():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
    data = request.get_json()
    dentist = data.get("dentist")
    aid = data.get("aid")
    query = "insert into dentist ( dentist, aid ) values ( %s, %s )"
    cursor.execute(query, (dentist, aid))

    conn.commit()
    cursor.close()
    conn.close()
    return {"message": "Success"}


@app.route("/additionalServices", methods=["POST"])
def additionalServices():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
    data = request.get_json()
    additionalServices = data.get("additionalServices")
    appointment_id = data.get("aid")
    user_id = data.get("user_id")

    query = "insert into service (user_id, appointment_id, service_type) values (%s, %s, %s)"
    cursor.execute(query, (user_id, appointment_id, additionalServices))
    conn.commit()

    cursor.close()
    conn.close()
    return {"message": "Success"}


@app.route("/getServices", methods=["GET"])
def getServices():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)

    query = "select * from service_type"
    cursor.execute(query)
    result = cursor.fetchall()

    cursor.close()
    conn.close()
    return jsonify(result)


@app.route("/getFindings", methods=["GET"])
def getFindings():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
    aid = request.args.get("aid")

    getToothConditionOfUser = "select tc.* from tooth_condition tc join appointment_backup ab on tc.user_id = ab.user_id where ab.aid = %s "
    cursor.execute(getToothConditionOfUser, (aid,))
    result = cursor.fetchall()
    return result


@app.route("/getAppointedCustomer", methods=["GET"])
def getAppointedCustomer():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
    aid = request.args.get("aid")

    query = """
    SELECT
    cd.*,
    ab.aid as aid,
    ab.status as status,
    DATE(cd.birthDay) AS birthDay,
    u.email AS email,
    GROUP_CONCAT(DISTINCT m.meds) AS medications,
    GROUP_CONCAT(DISTINCT t.taken) AS taken_list

    FROM appointment_backup ab
    join customer_detail cd ON ab.user_id = cd.user
    join user u ON ab.user_id = u.id
    join taken t on ab.user_id = t.user
    join medication m on ab.user_id = m.user
    WHERE ab.aid = %s limit 1
    """

    cursor.execute(query, (aid,))
    result = cursor.fetchall()
    cursor.close()
    conn.close()
    return result


@app.route("/notificationRead", methods=["GET"])
def notificationRead():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)

    user_id = request.args.get("user_id")
    query = "update notification set isRead = 1 where sentTo = %s"
    cursor.execute(query, (user_id,))
    affected = cursor.rowcount
    conn.commit()

    cursor.close()
    conn.close()
    return {"affected": affected}


@app.route("/getReason", methods=["GET"])
def getReason():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)

    aid = request.args.get("aid")
    query = "select * from notification where aid = %s"
    cursor.execute(query, (aid,))
    result = cursor.fetchall()

    cursor.close()
    conn.close()
    return result[0]


@app.route("/rejectAppointment", methods=["GET"])
def rejectAppointment():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
    aid = request.args.get("aid")
    user_id = request.args.get("user_id")
    reason = request.args.get("reason")

    cursor.execute("update appointment_backup set status = 4 WHERE aid = %s", (aid,))
    cursor.execute(
        """
        INSERT INTO notification (`aid`, `message`, `reason`, `sentBy`, `sentTo`)
        SELECT %s, %s, %s, %s, user_id
        FROM appointment_backup
        WHERE aid = %s
        """,
        (aid, "Appointment Rejected", reason, user_id, aid),
    )

    conn.commit()
    cursor.close()
    conn.close()

    return {"data": "success"}


@app.route("/getComplaints", methods=["GET"])
def getComplaints():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)

    aid = request.args.get("aid")

    cursor.execute("select * from complaints where aid = %s", (aid,))

    result = cursor.fetchall()
    cursor.close()
    conn.close()

    return result


@app.route("/saveComplaints", methods=["POST"])
def saveComplaints():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)

    data = request.get_json()
    aid = data.get("aid")
    complaints = data.get("complaints")
    cursor.execute(
        "insert into complaints (`complaint`, `aid` ) values (%s, %s) ",
        (complaints, aid),
    )

    conn.commit()
    cursor.close()
    conn.close()

    return {"data": "success"}


@app.route("/rescheduleRequest", methods=["POST"])
def rescheduleRequest():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
    data = request.get_json()
    appointment = data.get("newAppointment")
    try:
        conn.start_transaction()
        cursor.execute(
            "select * from appointment_backup where aid = %s", (appointment["prevAid"],)
        )
        result = cursor.fetchall()
        cursor.execute(
            "update appointment_backup set status = 4 where status in (1,6,7) and user_id = %s",
            (result[0]["user_id"],),
        )
        cursor.execute(
            "update appointment_backup set status = 8 where status = 3 and user_id = %s",
            (result[0]["user_id"],),
        )

        if appointment["role"] == "user":
            cursor.execute(
                """insert into appointment_backup (user_id, appointment_start, appointment_end , note, status, date)
                select %s, %s, %s, note, %s, %s from appointment_backup where aid = %s""",
                (
                    appointment["user_id"],
                    appointment["start"],
                    appointment["end"],
                    6,
                    appointment["date"],
                    appointment["prevAid"],
                ),
            )
            inserted_id = cursor.lastrowid
            if inserted_id:
                cursor.execute(
                    """insert into service (user_id, appointment_id, service_type)
                    select %s, %s, service_type from service where appointment_id = %s""",
                    (
                        appointment["user_id"],
                        inserted_id,
                        appointment["prevAid"],
                    ),
                )
                cursor.execute(
                    """insert into payment (aid, payment)
                    select %s, payment from payment where aid = %s""",
                    (
                        inserted_id,
                        appointment["prevAid"],
                    ),
                )
                cursor.execute(
                    """insert into complaints (aid, complaint)
                    select %s, complaint from complaints where aid = %s""",
                    (
                        inserted_id,
                        appointment["prevAid"],
                    ),
                )
                cursor.execute(
                    """insert into dentist (aid, dentist)
                    select %s, dentist from dentist where aid = %s""",
                    (
                        inserted_id,
                        appointment["prevAid"],
                    ),
                )
            cursor.execute(
                "insert into `notification`(`aid`, `message`, `reason`, `sentBy`,`sentTo` ) select %s ,%s, %s, %s, id from user where role = 'admin'",
                (
                    inserted_id,
                    "Appointment Reschedule",
                    appointment["reason"],
                    appointment["user_id"],
                ),
            )

        else:
            cursor.execute(
                """insert into appointment_backup (user_id, appointment_start, appointment_end , note, status, date)
                select %s, %s, %s, note, %s, %s from appointment_backup where aid = %s""",
                (
                    result[0]["user_id"],
                    appointment["start"],
                    appointment["end"],
                    7,
                    appointment["date"],
                    appointment["prevAid"],
                ),
            )
            inserted_id = cursor.lastrowid
            if inserted_id:
                cursor.execute(
                    """insert into service (user_id, appointment_id, service_type)
                    select %s, %s, service_type from service where appointment_id = %s""",
                    (
                        result[0]["user_id"],
                        inserted_id,
                        appointment["prevAid"],
                    ),
                )
                cursor.execute(
                    """insert into payment (aid, payment)
                    select %s, payment from payment where aid = %s""",
                    (
                        inserted_id,
                        appointment["prevAid"],
                    ),
                )
                cursor.execute(
                    """insert into complaints (aid, complaint)
                    select %s, complaint from complaints where aid = %s""",
                    (
                        inserted_id,
                        appointment["prevAid"],
                    ),
                )
                cursor.execute(
                    """insert into dentist (aid, dentist)
                    select %s, dentist from dentist where aid = %s""",
                    (
                        inserted_id,
                        appointment["prevAid"],
                    ),
                )

            cursor.execute(
                "insert into `notification`(`aid`, `message`,  `reason`, `sentBy`,`sentTo` ) select %s, %s, %s, %s, user_id from appointment_backup where aid = %s",
                (
                    inserted_id,
                    "Appointment Reschedule",
                    appointment["reason"],
                    appointment["user_id"],
                    appointment["prevAid"],
                ),
            )

        conn.commit()
    except Exception as e:
        conn.rollback()
        print(e)
        return {"error": str(e)}, 500

    finally:
        cursor.close()
        conn.close()
        return {"response": "message"}


@app.route("/approveAppointment", methods=["POST"])
def approveAppointment():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
    data = request.get_json()
    aid = data.get("aid")
    user_id = data.get("user_id")
    role = data.get("role")
    overlapId = data.get("overlapId") or []

    try:
        conn.start_transaction()
        if len(overlapId) > 0:
            for oid in overlapId:
                cursor.execute(
                    "UPDATE appointment_backup SET status = 4 WHERE aid = %s", (oid,)
                )
                cursor.execute(
                    """
                    INSERT INTO notification (`aid`, `message`, `reason`, `sentBy`, `sentTo`)
                    SELECT %s, %s, %s, %s, user_id
                    FROM appointment_backup
                    WHERE aid = %s
                    """,
                    (
                        oid,
                        "Appointment Cancelled",
                        "Other appointment has been approved",
                        user_id,
                        oid,
                    ),
                )
        cursor.execute("select * from appointment_backup where aid = %s", (aid,))
        result = cursor.fetchall()

        cursor.execute(
            "update appointment_backup SET status = 2 where aid = %s", (aid,)
        )

        cursor.execute(
            "update appointment_backup set status = 4 where status in (1,2,6,7,8,9,10) and user_id = %s and aid != %s",
            (result[0]["user_id"], aid),
        )
        cursor.execute(
            "update appointment_backup set status = 10 where status in (3) and aid = %s",
            (aid,),
        )

        if role == "admin":
            cursor.execute(
                """
            INSERT INTO notification (`aid`, `message`, `sentBy`, `sentTo`)
            SELECT %s, %s, %s, user_id
            FROM appointment_backup
            WHERE aid = %s
            """,
                (aid, "Appointment Approved", user_id, aid),
            )
        else:
            cursor.execute(
                """
            INSERT INTO notification (`aid`, `message`, `sentBy`, `sentTo`)
            SELECT %s, %s, %s, id
            FROM user
            WHERE role = 'admin'
            """,
                (aid, "Appointment Approved", user_id),
            )

        conn.commit()

    except Exception as e:
        conn.rollback()
        return {"error": str(e)}, 500

    finally:
        cursor.close()
        conn.close()

    return {"data": "success"}


@app.route("/getAppointmentServices", methods=["GET"])
def getAppointmentServices():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
    aid = request.args.get("aid")
    cursor.execute(
        """
        select st.*, concat(cd.firstName, ' ', cd.lastName) as fullname
        from service s
        join service_type st on st.id = s.service_type
        join customer_detail cd on cd.user = s.user_id
        where s.appointment_id = %s
        """,
        (aid,),
    )

    result = cursor.fetchall()
    cursor.close()
    conn.close()
    return jsonify(result)


@app.route("/getAppointments", methods=["GET"])
def getAppointment():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
    role = request.args.get("role")
    user_id = request.args.get("user_id")
    cursor.execute(
        "update appointment_backup set status = 5 where concat(date, ' ', appointment_start) < now() and status not in (3,4,5,8,9,10)"
    )
    conn.commit()

    if role == "user":
        cursor.execute(
            """select
        ab.aid as id,
        concat('Appointments ', ab.aid) AS groupId,
        ast.status_name AS title,
        CONCAT(ab.date, 'T', ab.appointment_start) AS start,
        CONCAT(ab.date, 'T', ab.appointment_end) AS end
        FROM appointment_backup ab
        right join appointment_status ast ON ast.id = ab.status
        WHERE ab.status NOT IN (4, 5)
        """
        )
    else:
        cursor.execute(
            """select
        ab.aid as id,
        concat('Appointments ', ab.aid) AS groupId,
        ast.status_name AS title,
        CONCAT(ab.date, 'T', ab.appointment_start) AS start,
        CONCAT(ab.date, 'T', ab.appointment_end) AS end
        FROM appointment_backup ab
        right join appointment_status ast ON ast.id = ab.status
        """
        )

    result = cursor.fetchall()
    cursor.close()
    conn.close()
    return jsonify(result)


@app.route("/requestAppointment", methods=["POST"])
def requestAppointment():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
    data = request.get_json()
    appointment = data.get("newAppointment")
    cursor.execute(
        "insert into appointment_backup (user_id, appointment_start, appointment_end , note, status, date) values (%s,%s,%s,%s,%s,%s)",
        (
            appointment["user_id"],
            appointment["start"],
            appointment["end"],
            appointment["note"],
            1,
            appointment["date"],
        ),
    )
    inserted_id = cursor.lastrowid
    if inserted_id:
        for service in appointment["services"]:
            cursor.execute(
                "insert into service (user_id, appointment_id, service_type) values (%s, %s, %s)",
                (appointment["user_id"], inserted_id, service),
            )

        cursor.execute(
            "insert into `notification`(`aid`, `message`, `sentBy`,`sentTo` ) select %s ,%s, %s, id from user where role = 'admin'",
            (
                inserted_id,
                "Appointment Request",
                appointment["user_id"],
            ),
        )

    conn.commit()
    cursor.close()
    conn.close()
    return {"response": "message"}


@app.route("/selectCustomer", methods=["GET"])
def selectCustomer():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)

    try:
        query = "SELECT * FROM customer_detail"
        cursor.execute(query)
        result = cursor.fetchall()
        cursor.close()
        conn.close()
        return result
    except Exception as e:
        return e


@app.route("/selectUser", methods=["GET"])
def selectUser():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)

    try:
        role = request.args.get("role")
        query_user = "select u.id as id from user u join customer_detail cd on u.id = cd.user where u.role = %s and cd.isValidated = 1"
        cursor.execute(query_user, (role,))
        users = cursor.fetchall()
        user_details = []
        for user in users:
            user_id = user["id"]

            cursor.execute(
                "SELECT * FROM customer_detail cd join user u on u.id = cd.user WHERE `user` = %s",
                (user_id,),
            )
            customer_detail = cursor.fetchall()

            cursor.execute("SELECT * FROM medication WHERE `user` = %s", (user_id,))
            medication = cursor.fetchall()

            cursor.execute("SELECT * FROM taken WHERE `user` = %s", (user_id,))
            taken = cursor.fetchall()

            user_details.append(
                {
                    "user": user,
                    "customer_detail": customer_detail,
                    "medication": medication,
                    "taken": taken,
                }
            )

        cursor.close()
        conn.close()
        return user_details

    except Exception as e:
        return {"error": str(e)}


@app.route("/setToothConditionChanges", methods=["GET"])
def setToothConditionChanges():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)

    legendChanges = json.loads(request.args.get("legendChanges"))
    shadeChanges = json.loads(request.args.get("shadeChanges"))
    user_id = request.args.get("user_id")

    for item in shadeChanges:
        tooth = item["tooth"]
        value = item["value"]
        quadrant = item["quadrant"]
        cursor.execute(
            "select * from tooth_condition where user_id = %s and tooth = %s",
            (user_id, tooth),
        )
        result = cursor.fetchall()
        if result:
            resultID = result[0]["id"]
            query = f"update tooth_condition set {quadrant} = %s where id = %s"
            cursor.execute(query, (value, resultID))
        else:
            query = f"insert into tooth_condition (`user_id`, `tooth`, `{quadrant}`) values (%s, %s, %s)"
            cursor.execute(query, (user_id, tooth, 1))

    for item in legendChanges:
        tooth = item["tooth"]
        legend = item["value"]
        cursor.execute(
            "select * from tooth_condition where user_id = %s and tooth = %s",
            (user_id, tooth),
        )
        result = cursor.fetchall()
        if result:
            cursor.execute(
                "update tooth_condition set legend = %s where user_id = %s and tooth = %s ",
                (legend, user_id, tooth),
            )
        else:
            cursor.execute(
                "insert into tooth_condition (`user_id`, `tooth`, `legend`) values (%s, %s, %s)",
                (user_id, tooth, legend),
            )

    conn.commit()
    cursor.close()
    conn.close()

    return {"message": "Changed conditions"}


@app.route("/register", methods=["GET"])
def register():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)

    username = request.args.get("username")
    password = request.args.get("password")
    email = request.args.get("email")
    role = request.args.get("role")

    query = (
        "INSERT INTO user (username, password , role, email) VALUES (%s, %s, %s ,%s)"
    )

    cursor.execute(query, (username, password, role, email))
    inserted_id = cursor.lastrowid
    conn.commit()

    if role == "user":
        cursor.execute("INSERT INTO customer_detail (user) VALUES (%s)", (inserted_id,))
        conn.commit()

    cursor.close()
    conn.close()
    return {"message": "Registration complete", "inserted_id": inserted_id}


@app.route("/checkEmail", methods=["GET"])
def checkEmail():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
    email = request.args.get("email")
    query = " select * from user where email = %s"
    cursor.execute(query, (email,))
    result = cursor.fetchall()
    cursor.close()
    conn.close()
    return result


@app.route("/login", methods=["GET"])
def login():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
    username = request.args.get("username")

    query = """
    SELECT * FROM user WHERE username=%s
    """
    cursor.execute(query, (username,))
    result = cursor.fetchall()

    if result is None:
        return "User not found"

    cursor.close()
    conn.close()
    return result


@app.route("/addCustomer", methods=["GET"])
def addCustomer():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)

    id = request.args.get("id")
    firstName = request.args.get("firstName")
    middleName = request.args.get("middleName")
    lastName = request.args.get("lastName")
    nickName = request.args.get("nickName")
    address = request.args.get("address")
    contactNumber = request.args.get("contactNumber")
    facebook = request.args.get("facebook")
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
    isBeingTreated = request.args.get("isBeingTreated")
    isHospitalized = request.args.get("isHospitalized")
    isAllergy = request.args.get("isAllergy")
    menstrual = request.args.get("menstrual")
    isPregnant = request.args.get("isPregnant")
    isBreastfeeding = request.args.get("isBreastfeeding")
    additionalInformation = request.args.get("additionalInformation")

    conditions = request.args.get("conditions")
    conditions = json.loads(conditions)

    taken = request.args.get("taken")
    taken = json.loads(taken)

    try:
        query = """
            UPDATE customer_detail
            SET
            firstName = %s,
            middleName = %s,
            lastName = %s,
            nickName = %s,
            address = %s,
            contactNumber = %s,
            facebook = %s,
            birthDay = %s,
            nationality = %s,
            age = %s,
            gender = %s,
            civilStatus = %s,
            occupation = %s,
            employer = %s,
            clinic = %s,
            prevClinic = %s,
            emergencyFirstname = %s,
            emergencyLastname = %s,
            relationship = %s,
            emergencyContactNumber = %s,
            isBeingTreated= %s,
            isHospitalized= %s,
            isAllergy= %s,
            menstrual= %s,
            isPregnant= %s,
            isBreastfeeding= %s,
            additionalInformation= %s,
            isValidated = %s
            WHERE `user` = %s
            """
        cursor.execute(
            query,
            (
                firstName,
                middleName,
                lastName,
                nickName,
                address,
                contactNumber,
                facebook,
                birthDay,
                nationality,
                age,
                gender,
                civilStatus,
                occupation,
                employer,
                clinic,
                prevClinic,
                emergencyFirstname,
                emergencyLastname,
                relationship,
                emergencyContactNumber,
                isBeingTreated,
                isHospitalized,
                isAllergy,
                menstrual,
                isPregnant,
                isBreastfeeding,
                additionalInformation,
                1,
                id,
            ),
        )
        cursor.execute("DELETE FROM taken WHERE `user` = %s", (id,))
        cursor.execute("DELETE FROM medication  WHERE `user` = %s", (id,))
        if taken:
            for item in taken:
                cursor.execute(
                    "INSERT INTO taken (`user`, `taken`) VALUES (%s, %s)", (id, item)
                )
        if conditions:
            for condition in conditions:
                cursor.execute(
                    "INSERT INTO medication (`user`, `meds`) VALUES (%s, %s)",
                    (id, condition),
                )

        conn.commit()
        affected = cursor.rowcount
        cursor.close()
        conn.close()
        return "Details inserted"

    except Exception as e:
        cursor.rollback()
        return f"Details insert error occurred: {e}"


if __name__ == "__main__":
    app.run(debug=True)
