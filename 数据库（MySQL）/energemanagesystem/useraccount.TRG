TYPE=TRIGGERS
triggers='CREATE DEFINER=`root`@`localhost` TRIGGER delete_student_trigger\nAFTER DELETE ON useraccount\nFOR EACH ROW\nBEGIN\n    DELETE FROM student WHERE studentID = OLD.studentID;\nEND'
sql_modes=1436549152
definers='root@localhost'
client_cs_names='utf8mb4'
connection_cl_names='utf8mb4_unicode_ci'
db_cl_names='utf8_unicode_ci'
created=168727858707
