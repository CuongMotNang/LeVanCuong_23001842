<?php

/**
 * BÀI 2 – QUẢN LÝ VÉ XEM PHIM
 * Chạy: php bai2_ve_xem_phim.php (hoặc mở qua trình duyệt / server PHP)
 */

// ---------- Hàm tiện ích ----------

function printLine(string $text = ''): void
{
    echo $text . (PHP_SAPI === 'cli' ? PHP_EOL : '<br>' . PHP_EOL);
}

function formatMoney(float $amount): string
{
    return number_format($amount, 0, ',', '.') . ' VND';
}

// ---------- Class Movie ----------

class Movie
{
    private int $id;
    private string $title;
    private float $price;
    private int $totalSeats;
    private int $availableSeats;

    public function __construct($id, $title, $price, $totalSeats)
    {
        $title = is_scalar($title) ? trim((string) $title) : '';

        if (!is_numeric($id) || (int) $id != $id) {
            throw new InvalidArgumentException('Mã phim phải là số nguyên.');
        }
        if ($title === '') {
            throw new InvalidArgumentException('Tên phim không được để trống.');
        }
        if (!is_numeric($price) || !is_finite((float) $price) || $price <= 0) {
            throw new InvalidArgumentException("Phim '$title': giá vé phải là số lớn hơn 0.");
        }
        if (!is_numeric($totalSeats) || (int) $totalSeats != $totalSeats || $totalSeats <= 0) {
            throw new InvalidArgumentException("Phim '$title': tổng số ghế phải là số nguyên lớn hơn 0.");
        }

        $this->id = (int) $id;
        $this->title = $title;
        $this->price = (float) $price;
        $this->totalSeats = (int) $totalSeats;
        $this->availableSeats = (int) $totalSeats; // ban đầu còn đủ ghế
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAvailableSeats(): int
    {
        return $this->availableSeats;
    }

    // Trả về thông báo lỗi nếu số lượng vé không phải số nguyên > 0
    private function getQuantityError($quantity): ?string
    {
        if (!is_numeric($quantity) || (int) $quantity != $quantity || $quantity <= 0) {
            return 'Số lượng vé phải là số nguyên lớn hơn 0.';
        }
        return null;
    }

    /**
     * Đặt vé. Không hợp lệ thì in thông báo, không đổi dữ liệu và trả về false.
     */
    public function bookTicket($quantity): bool
    {
        $error = $this->getQuantityError($quantity);
        if ($error !== null) {
            printLine("[LỖI] Đặt vé phim '{$this->title}': $error");
            return false;
        }
        $quantity = (int) $quantity;

        if ($quantity > $this->availableSeats) {
            printLine(
                "[LỖI] Phim '{$this->title}' chỉ còn {$this->availableSeats} ghế, không thể đặt $quantity vé."
            );
            return false;
        }

        $this->availableSeats -= $quantity;
        printLine("[OK] Đã đặt $quantity vé phim '{$this->title}'.");
        return true;
    }

    /**
     * Hủy vé. Không hợp lệ thì in thông báo, không đổi dữ liệu và trả về false.
     */
    public function cancelTicket($quantity): bool
    {
        $error = $this->getQuantityError($quantity);
        if ($error !== null) {
            printLine("[LỖI] Hủy vé phim '{$this->title}': $error");
            return false;
        }
        $quantity = (int) $quantity;

        if ($quantity > $this->getSoldSeats()) {
            printLine(
                "[LỖI] Phim '{$this->title}' chỉ bán được {$this->getSoldSeats()} vé, không thể hủy $quantity vé."
            );
            return false;
        }

        $this->availableSeats += $quantity;
        printLine("[OK] Đã hủy $quantity vé phim '{$this->title}'.");
        return true;
    }

    public function getSoldSeats(): int
    {
        return $this->totalSeats - $this->availableSeats;
    }

    public function getRevenue(): float
    {
        return $this->getSoldSeats() * $this->price;
    }

    public function displayInfo(): void
    {
        printLine("Mã phim: {$this->id}");
        printLine("Tên phim: {$this->title}");
        printLine('Giá vé: ' . formatMoney($this->price));
        printLine("Tổng số ghế: {$this->totalSeats}");
        printLine("Số ghế còn lại: {$this->availableSeats}");
        printLine('Số vé đã bán: ' . $this->getSoldSeats());
        printLine('Doanh thu: ' . formatMoney($this->getRevenue()));
    }
}

// ---------- Các function xử lý danh sách phim ----------

/**
 * Tìm phim theo ID. Không thấy, ID không hợp lệ hoặc danh sách rỗng đều trả về null.
 */
function findMovieById(array $movies, $id): ?Movie
{
    if (!is_numeric($id) || (int) $id != $id) {
        return null;
    }

    foreach ($movies as $movie) {
        if ($movie instanceof Movie && $movie->getId() === (int) $id) {
            return $movie;
        }
    }
    return null;
}

/**
 * Tổng doanh thu của mọi phim. Danh sách rỗng trả về 0.
 */
function getTotalRevenue(array $movies): float
{
    $total = 0;
    foreach ($movies as $movie) {
        if ($movie instanceof Movie) {
            $total += $movie->getRevenue();
        }
    }
    return $total;
}

/**
 * Phim bán được nhiều vé nhất (nếu hòa thì lấy phim đứng trước).
 * Danh sách rỗng trả về null.
 */
function getBestSellingMovie(array $movies): ?Movie
{
    $best = null;
    foreach ($movies as $movie) {
        if (!($movie instanceof Movie)) {
            continue;
        }
        if ($best === null || $movie->getSoldSeats() > $best->getSoldSeats()) {
            $best = $movie;
        }
    }
    return $best;
}

// ---------- Hàm hỗ trợ chương trình chính ----------

function bookTicketById(array $movies, $id, $quantity): void
{
    $movie = findMovieById($movies, $id);
    if ($movie === null) {
        printLine("[LỖI] Không tìm thấy phim có mã '$id'.");
        return;
    }
    $movie->bookTicket($quantity);
}

function cancelTicketById(array $movies, $id, $quantity): void
{
    $movie = findMovieById($movies, $id);
    if ($movie === null) {
        printLine("[LỖI] Không tìm thấy phim có mã '$id'.");
        return;
    }
    $movie->cancelTicket($quantity);
}

function displayAllMovies(array $movies): void
{
    if (empty($movies)) {
        printLine('Danh sách phim đang trống.');
        return;
    }

    foreach ($movies as $movie) {
        $movie->displayInfo();
        printLine('------------------------------');
    }
}

function displayBestSellingMovie(array $movies): void
{
    $best = getBestSellingMovie($movies);

    if ($best === null) {
        printLine('Không có phim nào trong danh sách.');
        return;
    }
    if ($best->getSoldSeats() === 0) {
        printLine('Chưa có phim nào bán được vé.');
        return;
    }

    printLine('Phim bán được nhiều vé nhất:');
    $best->displayInfo();
}

// ---------- Chương trình chính ----------

if (PHP_SAPI !== 'cli') {
    echo '<meta charset="UTF-8">';
}

// 1. Tạo danh sách phim
$movies = [
    new Movie(1, 'Avengers', 100000, 100),
    new Movie(2, 'Avatar', 120000, 80),
    new Movie(3, 'Batman', 90000, 120),
];

// Chưa bán vé nào
printLine('--- Chưa bán vé nào ---');
displayBestSellingMovie($movies);
printLine();

// 2-4. Đặt vé, hủy vé
printLine('--- Đặt / hủy vé hợp lệ ---');
bookTicketById($movies, 1, 30);   // Avengers
bookTicketById($movies, 2, 20);   // Avatar
cancelTicketById($movies, 1, 5);  // hủy bớt vé Avengers
printLine();

// Các trường hợp không hợp lệ
printLine('--- Các trường hợp không hợp lệ ---');
bookTicketById($movies, 1, 0);      // đặt <= 0
bookTicketById($movies, 1, -3);     // đặt < 0
bookTicketById($movies, 1, 2.5);    // đặt số lẻ
bookTicketById($movies, 1, 'abc');  // đặt không phải số
bookTicketById($movies, 2, 500);    // đặt vượt số ghế còn lại
cancelTicketById($movies, 1, 0);    // hủy <= 0
cancelTicketById($movies, 1, 26);   // hủy nhiều hơn số vé đã bán (Avengers đã bán 25)
cancelTicketById($movies, 3, 10);   // Batman chưa bán vé nào
bookTicketById($movies, 99, 2);     // phim không tồn tại
bookTicketById($movies, 'abc', 2);  // ID không hợp lệ
printLine();

// Danh sách rỗng
printLine('--- Danh sách phim rỗng ---');
$emptyList = [];
printLine('Tổng doanh thu: ' . formatMoney(getTotalRevenue($emptyList)));
displayBestSellingMovie($emptyList);
printLine('Tìm phim mã 1: ' . (findMovieById($emptyList, 1) === null ? 'không tìm thấy (null)' : 'thấy'));
printLine();

// 5. Hiển thị thông tin tất cả phim
printLine('========== THÔNG TIN CÁC PHIM ==========');
displayAllMovies($movies);
printLine();

// 6. Tổng doanh thu
printLine('Tổng doanh thu tất cả phim: ' . formatMoney(getTotalRevenue($movies)));
printLine();

// 7. Phim bán chạy nhất
displayBestSellingMovie($movies);