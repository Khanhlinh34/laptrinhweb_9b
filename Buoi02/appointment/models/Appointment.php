<?php

class Appointment
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Lấy danh sách lịch hẹn
    public function getAll()
    {
        $sql = "SELECT * FROM appointments
                ORDER BY appointment_date, appointment_time";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm lịch hẹn
    public function create($data)
    {
        $sql = "INSERT INTO appointments
                (
                    student_name,
                    lecturer_name,
                    appointment_date,
                    appointment_time,
                    topic
                )
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            $data['student_name'],
            $data['lecturer_name'],
            $data['appointment_date'],
            $data['appointment_time'],
            $data['topic']
        ]);
    }

    // Kiểm tra lịch trùng
    public function checkDuplicate($date, $time, $lecturer)
    {
        $sql = "SELECT COUNT(*)
                FROM appointments
                WHERE appointment_date = ?
                AND appointment_time = ?
                AND lecturer_name = ?
                AND status IN ('pending', 'approved')";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $date,
            $time,
            $lecturer
        ]);

        return $stmt->fetchColumn() > 0;
    }
}