<?php
// Khai báo biến lưu tên và điểm của học sinh
$name = "Nguyễn Văn An";
$score = 7.5;

// Xếp loại học sinh bằng câu lệnh if else
if ($score >= 8) {
    $rank = "Giỏi";
} else if ($score >= 6.5 && $score < 8) {
    $rank = "Khá";
} else if ($score >= 6 && $score < 6.5) {
    $rank = "Trung bình";
} else {
    $rank = "Yếu";
}

// In kết quả bằng lệnh echo
echo "Học sinh: " . $name . "<br>";
echo "Điểm: " . $score . "<br>";
echo "Xếp loại: " . $rank;
?>
