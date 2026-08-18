<?php

function getStatusText($status)
{
    if ($status == 'pending') {
        return "Chờ xác nhận";
    } elseif ($status == 'approved') {
        return "Đã duyệt";
    } elseif ($status == 'completed') {
        return "Đã hoàn thành";
    } elseif ($status == 'cancelled') {
        return "Đã hủy";
    } elseif ($status == 'rejected') {
        return "Từ chối";
    }

    return "Không xác định";
}


$appointments = $appointments ?? [];

$errors = $errors ?? [];

$oldData = $oldData ?? [
    'student_name' => '',
    'lecturer_name' => '',
    'appointment_date' => '',
    'appointment_time' => '',
    'topic' => ''
];

$success = $success ?? '';

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Đặt lịch tư vấn</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 1000px;
            margin: auto;
        }


        /* =========================
           FORM
           ========================= */

        .form-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .header-icon {
            width: 48px;
            height: 48px;
            background: #e8f7ef;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 3px 0 0;
            color: #777;
            font-size: 14px;
        }

        h2 {
            font-size: 17px;
            margin: 20px 0;
        }

        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
            font-size: 15px;
        }

        input {
            width: 100%;
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #198754;
        }

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .error {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
        }

        .success {
            background: #e8f7ef;
            color: #198754;
            border: 1px solid #b7dfc5;
            padding: 10px 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        button {
            margin-top: 5px;
            padding: 12px 24px;
            background: #222;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }

        button:hover {
            background: #333;
        }


        /* =========================
           APPOINTMENT CARD
           ========================= */

        .appointment-list {
            margin-top: 22px;
        }

        .appointment-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .appointment-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 1px solid #ddd;
            padding-bottom: 12px;
            margin-bottom: 12px;
        }

        .appointment-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .appointment-icon {
            width: 40px;
            height: 40px;
            background: #e8f7ef;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .appointment-title h3 {
            margin: 0;
            font-size: 16px;
        }

        .appointment-title p {
            margin: 3px 0 0;
            color: #777;
            font-size: 12px;
        }

        .status {
            background: #fff0a8;
            color: #856404;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .detail-title {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .detail-row {
            display: grid;
            grid-template-columns: 120px 1fr;
            border-bottom: 1px solid #ddd;
            padding: 8px 0;
            font-size: 13px;
        }

        .detail-label {
            font-weight: bold;
        }

        .detail-value {
            color: #444;
        }

        .empty {
            background: white;
            padding: 25px;
            text-align: center;
            color: #777;
            border-radius: 10px;
        }


        @media (max-width: 700px) {

            .row {
                grid-template-columns: 1fr;
            }

            .detail-row {
                grid-template-columns: 100px 1fr;
            }

        }

    </style>

</head>


<body>

<div class="container">


    <!-- =================================
         FORM ĐẶT LỊCH
         ================================= -->

    <div class="form-card">

        <div class="header">

            <div class="header-icon">
                📅
            </div>

            <div>

                <h1>Đặt lịch tư vấn</h1>

                <p>
                    Đăng ký lịch hẹn với giảng viên
                </p>

            </div>

        </div>


        <h2>
            THÔNG TIN ĐẶT LỊCH
        </h2>


        <?php if (!empty($success)): ?>

            <div class="success">

                <?= htmlspecialchars(
                    $success,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>

            </div>

        <?php endif; ?>


        <form
            method="POST"
            action="index.php?action=store"
        >


            <!-- TÊN SINH VIÊN -->

            <div class="form-group">

                <label>
                    👤 Tên sinh viên
                </label>

                <input
                    type="text"
                    name="student_name"
                    placeholder="Nhập tên sinh viên"
                    value="<?= htmlspecialchars(
                        $oldData['student_name'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    required
                >

                <?php if (!empty($errors['student_name'])): ?>

                    <div class="error">

                        <?= htmlspecialchars(
                            $errors['student_name'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </div>

                <?php endif; ?>

            </div>


            <!-- TÊN GIẢNG VIÊN -->

            <div class="form-group">

                <label>
                    👨‍🏫 Tên giảng viên
                </label>

                <input
                    type="text"
                    name="lecturer_name"
                    placeholder="Nhập tên giảng viên"
                    value="<?= htmlspecialchars(
                        $oldData['lecturer_name'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    required
                >

                <?php if (!empty($errors['lecturer_name'])): ?>

                    <div class="error">

                        <?= htmlspecialchars(
                            $errors['lecturer_name'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </div>

                <?php endif; ?>

            </div>


            <!-- NGÀY + GIỜ -->

            <div class="row">


                <div class="form-group">

                    <label>
                        📅 Ngày hẹn
                    </label>

                    <input
                        type="date"
                        name="appointment_date"
                        value="<?= htmlspecialchars(
                            $oldData['appointment_date'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                        required
                    >

                    <?php if (!empty($errors['appointment_date'])): ?>

                        <div class="error">

                            <?= htmlspecialchars(
                                $errors['appointment_date'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </div>

                    <?php endif; ?>

                </div>


                <div class="form-group">

                    <label>
                        🕐 Giờ hẹn
                    </label>

                    <input
                        type="time"
                        name="appointment_time"
                        value="<?= htmlspecialchars(
                            $oldData['appointment_time'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                        required
                    >

                    <?php if (!empty($errors['appointment_time'])): ?>

                        <div class="error">

                            <?= htmlspecialchars(
                                $errors['appointment_time'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </div>

                    <?php endif; ?>

                </div>

            </div>


            <!-- CHỦ ĐỀ -->

            <div class="form-group">

                <label>
                    📝 Chủ đề cần tư vấn
                </label>

                <input
                    type="text"
                    name="topic"
                    placeholder="Ví dụ: Tư vấn đồ án PHP"
                    value="<?= htmlspecialchars(
                        $oldData['topic'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    required
                >

                <?php if (!empty($errors['topic'])): ?>

                    <div class="error">

                        <?= htmlspecialchars(
                            $errors['topic'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </div>

                <?php endif; ?>

            </div>


            <button type="submit">
                ĐẶT LỊCH
            </button>

        </form>

    </div>


    <!-- =================================
         DANH SÁCH LỊCH HẸN
         ================================= -->

    <div class="appointment-list">

        <?php foreach ($appointments as $appointment): ?>


            <div class="appointment-card">


                <!-- HEADER CARD -->

                <div class="appointment-header">


                    <div class="appointment-title">

                        <div class="appointment-icon">
                            📅
                        </div>

                        <div>

                            <h3>
                                <?= htmlspecialchars(
                                    $appointment['topic'] ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </h3>

                            <p>
                                Mã lịch hẹn:
                                <?= htmlspecialchars(
                                    $appointment['appointment_code']
                                    ?? 'LH' . rand(1, 999),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </p>

                            <?php if (!empty($appointment['created_at'])): ?>

                                <p>
                                    Đặt vào:
                                    <?= htmlspecialchars(
                                        $appointment['created_at'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </p>

                            <?php endif; ?>

                        </div>

                    </div>


                    <div class="status">

                        <?= htmlspecialchars(
                            getStatusText(
                                $appointment['status'] ?? 'pending'
                            ),
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </div>

                </div>


                <!-- CHI TIẾT -->

                <div class="detail-title">
                    THÔNG TIN LỊCH HẸN
                </div>


                <div class="detail-row">

                    <div class="detail-label">
                        🆔 Mã lịch hẹn
                    </div>

                    <div class="detail-value">

                        <?= htmlspecialchars(
                            $appointment['appointment_code']
                            ?? 'LH',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </div>

                </div>


                <div class="detail-row">

                    <div class="detail-label">
                        👤 Họ và tên
                    </div>

                    <div class="detail-value">

                        <?= htmlspecialchars(
                            $appointment['student_name'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </div>

                </div>


                <div class="detail-row">

                    <div class="detail-label">
                        👨‍🏫 Giảng viên
                    </div>

                    <div class="detail-value">

                        <?= htmlspecialchars(
                            $appointment['lecturer_name'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </div>

                </div>


                <div class="detail-row">

                    <div class="detail-label">
                        📝 Chủ đề tư vấn
                    </div>

                    <div class="detail-value">

                        <?= htmlspecialchars(
                            $appointment['topic'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </div>

                </div>


                <div class="detail-row">

                    <div class="detail-label">
                        📅 Ngày hẹn
                    </div>

                    <div class="detail-value">

                        <?= htmlspecialchars(
                            $appointment['appointment_date'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </div>

                </div>


                <div class="detail-row">

                    <div class="detail-label">
                        🕐 Khung giờ
                    </div>

                    <div class="detail-value">

                        <?= htmlspecialchars(
                            $appointment['appointment_time'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </div>

                </div>


                <div class="detail-row">

                    <div class="detail-label">
                        📋 Trạng thái
                    </div>

                    <div class="detail-value">

                        <?= htmlspecialchars(
                            getStatusText(
                                $appointment['status'] ?? 'pending'
                            ),
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </div>

                </div>


            </div>


        <?php endforeach; ?>


        <?php if (empty($appointments)): ?>

            <div class="empty">
                Chưa có lịch hẹn nào.
            </div>

        <?php endif; ?>


    </div>

</div>

</body>

</html>