import mysql.connector
db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="salologan"
)
cursor = db.cursor()
query ="""
    SELECT username, id FROM user WHERE username=admin
    """
cursor.execute(query)
result = cursor.fetchone()

print(result)