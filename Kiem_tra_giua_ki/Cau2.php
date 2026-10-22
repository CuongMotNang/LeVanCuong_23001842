<?php
// Khai báo mảng chứa giá của các sản phẩm
$prices = [150000, 250000, 99000, 320000, 175000, 80000];

// Hàm tính tổng giá trị của tất cả sản phẩm (dùng vòng lặp foreach)
function calculateTotal($prices) {
    $total = 0;
    foreach ($prices as $price) {
        $total += $price;
    }
    return $total;
}

// Hàm tìm giá sản phẩm cao nhất (dùng vòng lặp foreach)
function findMaxPrice($prices) {
    $max = $prices[0];
    foreach ($prices as $price) {
        if ($price > $max) {
            $max = $price;
        }
    }
    return $max;
}

// Gọi hai hàm và lưu kết quả
$total = calculateTotal($prices);
$maxPrice = findMaxPrice($prices);

// Hiển thị kết quả
echo "Danh sách giá: " . implode(", ", $prices) . "<br>";
echo "Tổng giá trị các sản phẩm: " . number_format($total) . " VNĐ<br>";
echo "Giá sản phẩm cao nhất: " . number_format($maxPrice) . " VNĐ";
?>
