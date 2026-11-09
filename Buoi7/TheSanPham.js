// Component <the-san-pham> — khai báo toàn cục để file index.html có thể đăng ký trước khi mount.
const TheSanPham = {
  props: {
    ten:   { type: String, required: true },            // bắt buộc
    gia:   { type: Number, required: true },
    anh:   { type: String, default: '' },               // có giá trị mặc định
    mota:  { type: String, default: 'Chưa có mô tả.' }, // có giá trị mặc định
    tonKho:{ type: Number, default: 0 },
  },
  data() {
    // Trạng thái riêng của từng thẻ
    return { moMoTa: false };
  },
  computed: {
    hetHang() { return this.tonKho <= 0; },
    giaDep() { return this.gia.toLocaleString('vi-VN') + ' đ'; },
  },
  template: `
    <div class="the" :class="{ 'het-hang': hetHang }">
      <img v-if="anh" :src="anh" :alt="ten">
      <h3>{{ ten }}</h3>
      <p class="gia">{{ giaDep }}</p>
      <p>
        <span v-if="hetHang" class="nhan het">Hết hàng</span>
        <span v-else class="nhan con">Còn {{ tonKho }} sản phẩm</span>
      </p>
      <button @click="moMoTa = !moMoTa">{{ moMoTa ? 'Ẩn mô tả' : 'Xem mô tả' }}</button>
      <button :disabled="hetHang" @click="$emit('mua', ten)">Mua</button>
      <p v-if="moMoTa" class="mota">{{ mota }}</p>
    </div>
  `,
  emits: ['mua'],
};
