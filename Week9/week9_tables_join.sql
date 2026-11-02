
CREATE TABLE Student (
    ID INT PRIMARY KEY,
    Name VARCHAR(100),
    Major VARCHAR(100)
);

CREATE TABLE Transcript (
    ID INT,
    Course VARCHAR(100),
    Grade VARCHAR(2),
    FOREIGN KEY (ID) REFERENCES Student(ID)
);

INSERT INTO Student VALUES
(101, 'Alex Johnson', 'Information Technology'),
(102, 'Sarah Williams', 'Computer Science'),
(103, 'Michael Brown', 'Business'),
(104, 'Emily Davis', 'Cybersecurity');

INSERT INTO Transcript VALUES
(101, 'IT202', 'A'),
(101, 'IT340', 'B'),
(102, 'CS100', 'A'),
(102, 'CS113', 'B'),
(103, 'MGMT216', 'A'),
(103, 'ECON201', 'B'),
(104, 'IT220', 'A'),
(104, 'IT230', 'B');

SELECT
    Student.Name,
    Student.ID,
    Student.Major,
    Transcript.Course,
    Transcript.Grade
FROM Student
INNER JOIN Transcript
ON Student.ID = Transcript.ID
ORDER BY Student.ID, Transcript.Course;
