from flask import Flask, request
from datetime import date, datetime, timedelta
from flask_cors import CORS
import mysql.connector
import logging
import hashlib
import json

app = Flask(__name__)
CORS(app)
today = date.today()
db_config = {
    "host": "localhost",
    "user": "root",
    "password": "",
    "database": "salologan",
}


@app.route("/getWaiting", methods=["GET"])
def getWaiting():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)

    date = request.args.get("date")
    start = request.args.get("start")

    query = """SELECT 
            ab.aid as aid,
            ab.user_id as user_id,
            concat(cd.firstName, ' ' , cd.lastName) as name,
            cd.age as age,
            cd.gender as gender,
            cd.contactNumber as number,
            cd.address as address,
            n.reason as reason,
            n.message as message

            FROM appointment_backup ab 
            join customer_detail cd on cd.user = ab.user_id
            join notification n on n.aid = ab.aid  
            WHERE ab.date = %s 
            AND ab.appointment_start = %s 
            AND ab.status IN (1, 6)
            """
    cursor.execute(query, (date, start))
    result = cursor.fetchall()

    cursor.close()
    conn.close()

    return result


@app.route("/changeStatusaid", methods=["GET"])
def changeStatusaid():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
    aid = request.args.get("aid")

    query = "UPDATE appointment_backup SET status = 6 WHERE aid = %s"
    cursor.execute(query, (aid,))
    result = cursor.rowcount
    cursor.close()
    conn.close()
    conn.commit()

    return "success"


@app.route("/cancelBooked", methods=["GET"])
def cancelBooked():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)

    aid = request.args.get("aid")
    reason = request.args.get("reason")
    user_id = request.args.get("user_id")

    cursor.execute("SELECT id FROM user WHERE role = 'admin'")
    admins = cursor.fetchall()

    insert_query = """
        INSERT INTO notification (aid, message, reason, sentBy, sentTo)
        VALUES (%s, %s, %s, %s, %s)
    """
    for admin in admins:
        cursor.execute(
            insert_query, (aid, "Cancel Request", reason, user_id, admin["id"])
        )

    cursor.execute("UPDATE appointment_backup SET status = 8 WHERE aid = %s", (aid,))
    conn.commit()

    cursor.close()
    conn.close()

    return {"success": True, "message": "Appointment cancelled successfully."}


@app.route("/rCancelApproval", methods=["GET"])
def rCancelApproval():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
    aid = request.args.get("aid")
    admin_id = request.args.get("admin_id")
    user_id = request.args.get("user_id")
    answer = request.args.get("answer")

    if answer == "Yes":
        query = "UPDATE appointment_backup SET status = 4 WHERE aid = %s"
        cursor.execute(query, (aid,))
        result = cursor.rowcount

        notificationQuery = "insert into notification ( `aid`, `message`, `sentBy`, `sentTo`) values (%s, %s, %s, %s)"
        cursor.execute(
            notificationQuery, (aid, "Cancellation Request Approved", admin_id, user_id)
        )
        conn.commit()
    else:
        query = "UPDATE appointment_backup SET status = 2 WHERE aid = %s"
        cursor.execute(query, (aid,))
        result = cursor.rowcount

        notificationQuery = "insert into notification ( `aid`, `message`, `sentBy`, `sentTo`) values (%s, %s, %s, %s)"
        cursor.execute(
            notificationQuery, (aid, "Cancellation Request Denied", admin_id, user_id)
        )
        conn.commit()

    cursor.close()
    conn.close()
    return "success"


@app.route("/changeStatus", methods=["GET"])
def changeStatus():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
    aid = request.args.get("aid")
    query = "UPDATE appointment_backup SET status = 5 WHERE aid = %s"
    cursor.execute(query, (aid,))
    result = cursor.rowcount
    conn.commit()

    cursor.close()
    conn.close()
    return "success"


@app.route("/isAppointed", methods=["GET"])
def isAppointed():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
    user_id = request.args.get("user_id")

    query = (
        "SELECT * FROM appointment_backup WHERE user_id = %s AND status in (8,6,2,1)"
    )
    cursor.execute(query, (user_id,))
    result = cursor.fetchall()

    cursor.close()
    conn.close()
    return result


@app.route("/getCustomerDetails", methods=["GET"])
def getCustomerDetails():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)

    user_id = request.args.get("id")
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

    query = "UPDATE `appointment_backup` SET status = %s WHERE aid = %s "
    cursor.execute(query, (status, id))
    conn.commit()
    affected = cursor.rowcount

    if status == "4":
        message = "Appointment Cancelled"

    if status == "3":
        message = "Appointment Done"

    if status == "2":
        message = "Appointment Approved"

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
    SELECT 
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


@app.route("/additionalServices", methods=["GET"])
def additionalServices():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)

    additionalServices = request.args.get("additionalServices")
    appointment_id = request.args.get("appointment_id")
    user_id = request.args.get("user_id")

    query = "insert into service (user_id, appointment_id, service_type) values (%s, %s, %s)"
    cursor.execute(query, (user_id, appointment_id, additionalServices))
    conn.commit()

    cursor.close()
    conn.close()
    return "Success"


@app.route("/getServices", methods=["GET"])
def getServices():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)

    query = "select * from service_type"
    cursor.execute(query)
    result = cursor.fetchall()

    cursor.close()
    conn.close()
    return result


@app.route("/setFindings", methods=["GET"])
def setFindings():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
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

        cursor.execute(
            update_query,
            (
                tooth1,
                tooth2,
                tooth3,
                tooth4,
                tooth5,
                tooth6,
                tooth7,
                tooth8,
                tooth9,
                tooth10,
                tooth11,
                tooth12,
                tooth13,
                tooth14,
                tooth15,
                tooth16,
                tooth17,
                tooth18,
                tooth19,
                tooth20,
                tooth21,
                tooth22,
                tooth23,
                tooth24,
                tooth25,
                tooth26,
                tooth27,
                tooth28,
                tooth29,
                tooth30,
                tooth31,
                tooth32,
                user_id,
            ),
        )

        conn.commit()
        cursor.close()
        conn.close()
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

        cursor.execute(
            insert_query,
            (
                user_id,
                tooth1,
                tooth2,
                tooth3,
                tooth4,
                tooth5,
                tooth6,
                tooth7,
                tooth8,
                tooth9,
                tooth10,
                tooth11,
                tooth12,
                tooth13,
                tooth14,
                tooth15,
                tooth16,
                tooth17,
                tooth18,
                tooth19,
                tooth20,
                tooth21,
                tooth22,
                tooth23,
                tooth24,
                tooth25,
                tooth26,
                tooth27,
                tooth28,
                tooth29,
                tooth30,
                tooth31,
                tooth32,
            ),
        )
        conn.commit()
        inserted_id = cursor.lastrowid

        cursor.close()
        conn.close()
        return {"message": "Findings inserted.", "inserted_id": inserted_id}


@app.route("/getAppointedCustomer", methods=["GET"])
def getAppointedCustomer():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
    aid = request.args.get("aid")

    query = """
    SELECT
    ab.aid as aid,
    ab.status as status,
    cd.user AS user_id,
    cd.firstName AS firstname,
    cd.lastName AS lastname,
    cd.nickName AS nickname,
    cd.address AS address,
    cd.contactNumber AS contactNumber,
    cd.facebook AS facebook,
    DATE(cd.birthDay) AS birthDay,
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
    n.reason as reason,
    
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
    join notification n ON n.aid = ab.aid
    WHERE ab.aid = %s
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


@app.route("/cancelSingleAppointment", methods=["GET"])
def cancelSingleAppointment():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)

    aid = request.args.get("aid")
    user_id = request.args.get("user_id")
    admin_id = request.args.get("admin_id")
    reason = request.args.get("reason")

    query = "update appointment_backup set status = 4 where aid = %s"
    cursor.execute(query, (aid,))
    affected = cursor.rowcount

    notificationQuery = "insert into notification ( `aid`, `message`, `reason`, `sentBy`, `sentTo`) values (%s, %s, %s, %s, %s)"
    cursor.execute(
        notificationQuery, (aid, "Reschedule Cancelled", reason, admin_id, user_id)
    )
    conn.commit()

    lastAppointment = "select * from appointment_backup where user_id = %s and status = 2 order by aid desc limit 1"
    cursor.execute(lastAppointment, (user_id,))
    lastAppointment = cursor.fetchall()

    if lastAppointment:
        lastAppointment = lastAppointment[0]
        lastAid = lastAppointment["aid"]
        lastDate = lastAppointment["date"]
        lastStart = lastAppointment["appointment_start"]

        checkForBookedAppointment = "select * from appointment_backup where appointment_start = %s AND status = 2 AND aid != %s"
        cursor.execute(
            checkForBookedAppointment,
            (
                lastStart,
                lastAid,
            ),
        )
        hasOtherAppointment = cursor.fetchall()

        if hasOtherAppointment:
            notificationQuery = "insert into notification ( `aid`, `message`, `reason`, `sentBy`, `sentTo`) values (%s, %s, %s, %s, %s)"
            cursor.execute(
                notificationQuery,
                (
                    aid,
                    "All schedule Cancelled",
                    "The chosen time slot has already been assigned. Please schedule again",
                    admin_id,
                    user_id,
                ),
            )
            conn.commit()

            cursor.close()
            conn.close()
            return "already has appointment"
        else:
            updateQuery = "update appointment_backup set status = 1 where aid = %s and date = %s and appointment_start = %s"
            cursor.execute(updateQuery, (lastAid, lastDate, lastStart))
            conn.commit()

    cursor.close()
    conn.close()
    return {"affected": affected}


@app.route("/requestFeedback", methods=["GET"])
def requestFeedback():

    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)

    aid = request.args.get("aid")
    action = request.args.get("action")

    query = "select * from appointment_backup where `date` = %s AND status != 4 AND status != 5"
    cursor.execute(query, (date,))
    result = cursor.fetchall()

    cursor.close()
    conn.close()
    return result


@app.route("/getAppointment", methods=["GET"])
def getAppointment():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)

    date = request.args.get("date")

    query = "select * from appointment_backup where `date` = %s AND status != 4 AND status != 5"
    cursor.execute(query, (date,))
    result = cursor.fetchall()

    cursor.close()
    conn.close()
    return result


@app.route("/setAppointment", methods=["GET"])
def setAppointment():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
    user_id = request.args.get("user_id")
    startAppointment = request.args.get("startAppointment")
    endAppointment = request.args.get("endAppointment")
    note = request.args.get("note")
    serviceType = request.args.get("serviceType")
    date = request.args.get("date")
    reason = request.args.get("reason") or None
    aid = request.args.get("aid") or None

    admins = cursor.execute("select * from user where role = 'admin'")
    admins = cursor.fetchall()

    if aid and reason:
        getPreviousAppointmentStatus = (
            "select * from appointment_backup where user_id = %s"
        )
        cursor.execute(getPreviousAppointmentStatus, (user_id,))
        result = cursor.fetchall()

        for row in result:
            rowAid = row["aid"]
            rowStatus = row["status"]
            if rowStatus in ("1", "6"):
                cursor.execute(
                    "update appointment_backup set status = 4 where aid = %s", (rowAid,)
                )
                conn.commit()

        query = "INSERT INTO appointment_backup (user_id , appointment_start, appointment_end, note, status, date) VALUES (%s,%s,%s,%s,%s,%s)"
        cursor.execute(
            query, (user_id, startAppointment, endAppointment, note, 6, date)
        )
        inserted_id = cursor.lastrowid
        conn.commit()

        for admin in admins:
            cursor.execute(
                "insert into notification ( `aid`, `message`, `reason`, `sentBy`, `sentTo`) values (%s, %s, %s, %s, %s)",
                (inserted_id, "Reschedule Request", reason, user_id, admin["id"]),
            )
            conn.commit()

    else:
        if aid and user_id:
            cursor.execute("update appointment_backup set status = 10 where aid = %s", (aid,))
            status = 2
        else:
            status = 1

        query = "INSERT INTO appointment_backup (user_id , appointment_start, appointment_end, note, status, date) VALUES (%s,%s,%s,%s,%s,%s)"
        cursor.execute(query, (user_id, startAppointment, endAppointment, note, status, date))
        inserted_id = cursor.lastrowid
        conn.commit()

        query = "insert into service (user_id, appointment_id, service_type) values (%s, %s, %s)"
        cursor.execute(query, (user_id, inserted_id, serviceType))
        conn.commit()

        for admin in admins:
            cursor.execute(
                "insert into notification ( `aid`, `message`, `reason`, `sentBy`, `sentTo`) values (%s, %s, %s, %s, %s)",
                (
                    inserted_id,
                    "Appointment Request",
                    "Appointment Request",
                    user_id,
                    admin["id"],
                ),
            )
            conn.commit()

    hStartAppointment = hashlib.md5(startAppointment.encode()).hexdigest()
    hEndAppointment = hashlib.md5(endAppointment.encode()).hexdigest()
    hServiceType = hashlib.md5(serviceType.encode()).hexdigest()
    hNote = hashlib.md5(note.encode()).hexdigest()
    hdate = hashlib.md5(date.encode()).hexdigest()

    query = "INSERT INTO appointment (user_id , appointment_start, appointment_end, service_type, note, status, date) VALUES (%s,%s,%s,%s,%s,%s,%s)"
    cursor.execute(
        query,
        (user_id, hStartAppointment, hEndAppointment, hServiceType, hNote, 1, hdate),
    )
    conn.commit()

    query = "SELECT * FROM appointment_backup WHERE aid = %s"
    cursor.execute(query, (inserted_id,))
    result = cursor.fetchall()

    cursor.close()
    conn.close()
    return result


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
        query_user = "SELECT * FROM user WHERE role = %s"
        cursor.execute(query_user, (role,))
        users = cursor.fetchall()

        user_details = []
        for user in users:
            user_id = user["id"]

            cursor.execute(
                "SELECT * FROM customer_detail cd join user u on u.id = cd.user  WHERE `user` = %s",
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

    tooth_condition_query = "INSERT INTO tooth_conditions (user_id) VALUES (%s)"
    cursor.execute(tooth_condition_query, (inserted_id,))

    customer_query = "INSERT INTO customer_detail (user) VALUES (%s)"
    cursor.execute(customer_query, (inserted_id,))
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
