<?php

/**
 * BÀI 1 – XÂY DỰNG GIỎ HÀNG MUA SẮM
 * Chạy: php bai1_gio_hang.php (hoặc mở qua trình duyệt / server PHP)
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

// ---------- Class CartItem ----------

class CartItem
{
    private $name;
    private $price;
    private $quantity;

    // Constructor chỉ lưu dữ liệu; việc kiểm tra hợp lệ do getValidationError() đảm nhiệm
    public function __construct($name, $price, $quantity)
    {
        $this->name = is_scalar($name) ? trim((string) $name) : '';
        $this->price = $price;
        $this->quantity = $quantity;
    }

    /**
     * Trả về thông báo lỗi nếu dữ liệu không hợp lệ, null nếu hợp lệ.
     */
    public function getValidationError(): ?string
    {
        if ($this->name === '') {
            return 'Tên sản phẩm không được để trống.';
        }
        if (!is_numeric($this->price) || !is_finite((float) $this->price) || $this->price <= 0) {
            return "Sản phẩm '{$this->name}': đơn giá phải là số lớn hơn 0.";
        }
        if (!is_numeric($this->quantity) || (int) $this->quantity != $this->quantity || $this->quantity <= 0) {
            return "Sản phẩm '{$this->name}': số lượng phải là số nguyên lớn hơn 0.";
        }
        return null;
    }

    public function isValid(): bool
    {
        return $this->getValidationError() === null;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return (float) $this->price;
    }

    public function getQuantity(): int
    {
        return (int) $this->quantity;
    }

    // Thành tiền = đơn giá × số lượng (item không hợp lệ thì trả về 0)
    public function getTotal(): float
    {
        if (!$this->isValid()) {
            return 0;
        }
        return $this->getPrice() * $this->getQuantity();
    }
}

// ---------- Class ShoppingCart ----------

class ShoppingCart
{
    /** @var CartItem[] */
    private array $items = [];

    /**
     * Thêm sản phẩm vào giỏ. Không hợp lệ thì KHÔNG thêm, in thông báo và trả về false.
     */
    public function addItem($item): bool
    {
        if (!($item instanceof CartItem)) {
            printLine('[LỖI] Chỉ được thêm đối tượng CartItem vào giỏ hàng.');
            return false;
        }

        $error = $item->getValidationError();
        if ($error !== null) {
            printLine("[LỖI] Không thể thêm sản phẩm. $error");
            return false;
        }

        $this->items[] = $item;
        printLine("[OK] Đã thêm: {$item->getName()}");
        return true;
    }

    /**
     * Xóa sản phẩm theo tên (không phân biệt hoa/thường).
     * Không tìm thấy thì in thông báo và trả về false.
     */
    public function removeItem($name): bool
    {
        $name = is_scalar($name) ? trim((string) $name) : '';

        foreach ($this->items as $index => $item) {
            if (strcasecmp($item->getName(), $name) === 0) {
                unset($this->items[$index]);
                $this->items = array_values($this->items); // đánh lại chỉ số
                printLine("[OK] Đã xóa: {$item->getName()}");
                return true;
            }
        }

        printLine("[LỖI] Không tìm thấy sản phẩm '$name' trong giỏ hàng.");
        return false;
    }

    // Gọi getTotal() của từng CartItem; giỏ rỗng trả về 0
    public function calculateTotal(): float
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->getTotal();
        }
        return $total;
    }

    public function displayCart(): void
    {
        printLine('========== GIỎ HÀNG ==========');

        if (empty($this->items)) {
            printLine('Giỏ hàng đang trống.');
            printLine('Tổng tiền: ' . formatMoney($this->calculateTotal()));
            return;
        }

        foreach ($this->items as $index => $item) {
            printLine(sprintf(
                '%d. %s | Đơn giá: %s | SL: %d | Thành tiền: %s',
                $index + 1,
                $item->getName(),
                formatMoney($item->getPrice()),
                $item->getQuantity(),
                formatMoney($item->getTotal())
            ));
        }

        printLine('------------------------------');
        printLine('Tổng tiền: ' . formatMoney($this->calculateTotal()));
    }
}

// ---------- Chương trình chính ----------

if (PHP_SAPI !== 'cli') {
    echo '<meta charset="UTF-8">';
}

$cart = new ShoppingCart();

// Giỏ rỗng: calculateTotal() và removeItem() không được gây lỗi
printLine('--- Giỏ hàng rỗng ---');
$cart->displayCart();
$cart->removeItem('Laptop');
printLine();

// Thêm sản phẩm hợp lệ
printLine('--- Thêm sản phẩm ---');
$cart->addItem(new CartItem('Laptop', 15000000, 1));
$cart->addItem(new CartItem('Chuột không dây', 250000, 2));
$cart->addItem(new CartItem('Bàn phím cơ', 1200000, 1));
$cart->addItem(new CartItem('Tai nghe', 800000, 3));
printLine();

// Các trường hợp không hợp lệ
printLine('--- Thêm sản phẩm không hợp lệ ---');
$cart->addItem(new CartItem('Giá bằng 0', 0, 1));
$cart->addItem(new CartItem('Giá âm', -50000, 1));
$cart->addItem(new CartItem('Giá không phải số', 'abc', 1));
$cart->addItem(new CartItem('Số lượng bằng 0', 100000, 0));
$cart->addItem(new CartItem('Số lượng âm', 100000, -2));
$cart->addItem(new CartItem('Số lượng lẻ', 100000, 2.5));
$cart->addItem(new CartItem('   ', 100000, 1));
$cart->addItem('không phải CartItem');
printLine();

// Hiển thị giỏ hàng + tổng tiền
$cart->displayCart();
printLine();
printLine('Tổng tiền tính riêng bằng calculateTotal(): ' . formatMoney($cart->calculateTotal()));
printLine();

// Xóa sản phẩm
printLine('--- Xóa sản phẩm ---');
$cart->removeItem('Bàn phím cơ');
$cart->removeItem('Màn hình'); // không tồn tại
printLine();

// Hiển thị lại sau khi xóa
$cart->displayCart();