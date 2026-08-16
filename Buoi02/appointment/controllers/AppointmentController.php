<?php

class AppointmentController
{
    private $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    // Hiển thị danh sách lịch hẹn
    public function index()
    {
        $appointments = $this->model->getAll();

        require "views/appointment/index.php";
    }

    // Xử lý form đặt lịch
    public function store()
    {
        $studentName = trim($_POST['student_name'] ?? '');
        $lecturerName = trim($_POST['lecturer_name'] ?? '');
        $date = $_POST['appointment_date'] ?? '';
        $time = $_POST['appointment_time'] ?? '';
        $topic = trim($_POST['topic'] ?? '');

        // 1. Kiểm tra không được bỏ trống
        if (
            empty($studentName) ||
            empty($lecturerName) ||
            empty($date) ||
            empty($time) ||
            empty($topic)
        ) {
            die("Vui lòng nhập đầy đủ thông tin.");
        }

        // 2. Kiểm tra chủ đề
        if (strlen($topic) < 5) {
            die("Chủ đề tư vấn phải có ít nhất 5 ký tự.");
        }

        // 3. Không cho đặt lịch trong quá khứ
        if ($date < date('Y-m-d')) {
            die("Không thể đặt lịch trong quá khứ.");
        }

        // 4. Kiểm tra lịch trùng
        if ($this->model->checkDuplicate(
            $date,
            $time,
            $lecturerName
        )) {
            die("Giảng viên đã có lịch vào thời gian này.");
        }

        // 5. Tạo mảng dữ liệu
        $data = [
            'student_name' => $studentName,
            'lecturer_name' => $lecturerName,
            'appointment_date' => $date,
            'appointment_time' => $time,
            'topic' => $topic
        ];

        // 6. Lưu vào MySQL
        $this->model->create($data);

        // 7. Quay lại trang chính
        header("Location: index.php");
        exit;
    }
}