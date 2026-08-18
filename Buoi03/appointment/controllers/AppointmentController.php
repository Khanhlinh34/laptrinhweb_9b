<?php

class AppointmentController
{
    private $model;

    public function __construct($model)
    {
        $this->model = $model;
    }


    // ==============================
    // HIỂN THỊ FORM
    // ==============================

    public function index()
    {
        $errors = [];

        $oldData = [
            'student_name' => '',
            'lecturer_name' => '',
            'appointment_date' => '',
            'appointment_time' => '',
            'topic' => ''
        ];

        $success = '';

        // Lấy danh sách lịch hẹn hiện có
        $appointments = $this->model->getAll();

        require "views/appointment/index.php";
    }


    // ==============================
    // XỬ LÝ FORM
    // ==============================

    public function store()
    {
        $errors = [];


        // ==============================
        // 1. LẤY DỮ LIỆU TỪ FORM
        // ==============================

        $studentName = trim(
            $_POST['student_name'] ?? ''
        );

        $lecturerName = trim(
            $_POST['lecturer_name'] ?? ''
        );

        $date = trim(
            $_POST['appointment_date'] ?? ''
        );

        $time = trim(
            $_POST['appointment_time'] ?? ''
        );

        $topic = trim(
            $_POST['topic'] ?? ''
        );


        // ==============================
        // 2. CHUẨN HÓA DỮ LIỆU
        // ==============================

        $studentName = preg_replace(
            '/\s+/',
            ' ',
            $studentName
        );

        $lecturerName = preg_replace(
            '/\s+/',
            ' ',
            $lecturerName
        );

        $topic = preg_replace(
            '/\s+/',
            ' ',
            $topic
        );


        // ==============================
        // 3. LƯU DỮ LIỆU ĐÃ NHẬP
        // ==============================

        $oldData = [
            'student_name' => $studentName,
            'lecturer_name' => $lecturerName,
            'appointment_date' => $date,
            'appointment_time' => $time,
            'topic' => $topic
        ];

        $success = '';


        // ==============================
        // 4. KIỂM TRA TÊN SINH VIÊN
        // ==============================

        if ($studentName === '') {

            $errors['student_name'] =
                'Vui lòng nhập tên sinh viên.';

        } elseif (mb_strlen($studentName) < 2) {

            $errors['student_name'] =
                'Tên sinh viên phải có ít nhất 2 ký tự.';

        } elseif (mb_strlen($studentName) > 100) {

            $errors['student_name'] =
                'Tên sinh viên không được vượt quá 100 ký tự.';
        }


        // ==============================
        // 5. KIỂM TRA TÊN GIẢNG VIÊN
        // ==============================

        if ($lecturerName === '') {

            $errors['lecturer_name'] =
                'Vui lòng nhập tên giảng viên.';

        } elseif (mb_strlen($lecturerName) < 2) {

            $errors['lecturer_name'] =
                'Tên giảng viên phải có ít nhất 2 ký tự.';

        } elseif (mb_strlen($lecturerName) > 100) {

            $errors['lecturer_name'] =
                'Tên giảng viên không được vượt quá 100 ký tự.';
        }


        // ==============================
        // 6. KIỂM TRA NGÀY HẸN
        // ==============================

        if ($date === '') {

            $errors['appointment_date'] =
                'Vui lòng chọn ngày hẹn.';

        } else {

            $dateObject = DateTime::createFromFormat(
                'Y-m-d',
                $date
            );

            if (
                !$dateObject ||
                $dateObject->format('Y-m-d') !== $date
            ) {

                $errors['appointment_date'] =
                    'Ngày hẹn không đúng định dạng.';

            } elseif ($date < date('Y-m-d')) {

                $errors['appointment_date'] =
                    'Không thể đặt lịch trong quá khứ.';
            }
        }


        // ==============================
        // 7. KIỂM TRA GIỜ HẸN
        // ==============================

        if ($time === '') {

            $errors['appointment_time'] =
                'Vui lòng chọn giờ hẹn.';

        } elseif (
            !preg_match(
                '/^(?:[01]\d|2[0-3]):[0-5]\d$/',
                $time
            )
        ) {

            $errors['appointment_time'] =
                'Giờ hẹn không đúng định dạng.';
        }


        // ==============================
        // 8. KIỂM TRA CHỦ ĐỀ
        // ==============================

        if ($topic === '') {

            $errors['topic'] =
                'Vui lòng nhập chủ đề cần tư vấn.';

        } elseif (mb_strlen($topic) < 5) {

            $errors['topic'] =
                'Chủ đề tư vấn phải có ít nhất 5 ký tự.';

        } elseif (mb_strlen($topic) > 500) {

            $errors['topic'] =
                'Chủ đề tư vấn không được vượt quá 500 ký tự.';
        }


        // ==============================
        // 9. KIỂM TRA HTML / JAVASCRIPT
        // ==============================

        $fieldsToCheck = [
            'student_name' => $studentName,
            'lecturer_name' => $lecturerName,
            'topic' => $topic
        ];

        foreach ($fieldsToCheck as $field => $value) {

            if (
                preg_match(
                    '/<[^>]*>|javascript\s*:/i',
                    $value
                )
            ) {

                $errors[$field] =
                    'Dữ liệu không được chứa HTML hoặc JavaScript.';
            }
        }


        // ==============================
        // 10. NẾU CÓ LỖI
        // ==============================

        if (!empty($errors)) {

            // Vẫn lấy danh sách lịch hẹn hiện có
            $appointments = $this->model->getAll();

            // Hiển thị lại form
            // Dữ liệu cũ vẫn được giữ
            require "views/appointment/index.php";

            return;
        }


        // ==============================
        // 11. DỮ LIỆU HỢP LỆ
        // ==============================

        $success = 'Đặt lịch thành công!';


        // ==============================
        // 12. TẠO LỊCH HẸN TẠM THỜI
        // ==============================

        /*
         * Đề bài chưa yêu cầu lưu CSDL.
         *
         * Vì vậy chỉ tạo dữ liệu tạm thời
         * để hiển thị card ngay bên dưới form.
         */

        $newAppointment = [

            'appointment_code' =>
                'LH' . rand(1000, 9999),

            'student_name' =>
                $studentName,

            'lecturer_name' =>
                $lecturerName,

            'appointment_date' =>
                $date,

            'appointment_time' =>
                $time,

            'topic' =>
                $topic,

            'status' =>
                'pending',

            'created_at' =>
                date('Y-m-d H:i:s')
        ];


        // ==============================
        // 13. LẤY LỊCH HẸN HIỆN CÓ
        // ==============================

        $appointments = $this->model->getAll();


        // ==============================
        // 14. THÊM LỊCH VỪA ĐẶT
        // VÀO ĐẦU DANH SÁCH
        // ==============================

        array_unshift(
            $appointments,
            $newAppointment
        );


        // ==============================
        // 15. HIỂN THỊ LẠI TRANG
        // ==============================

        require "views/appointment/index.php";
    }
}