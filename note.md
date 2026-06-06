# Ghi chú tính năng mở rộng (Lab05 Bonus Features)

1. **CSRF Protection:** Tự động sinh và kiểm duyệt CSRF Token trên toàn bộ các yêu cầu POST (Thêm, Sửa, Xóa) để bảo vệ hệ thống khỏi tấn công giả mạo yêu cầu chéo trang.
2. **Soft Delete (Xóa mềm):** Sử dụng cột `deleted_at` để đánh dấu trạng thái ẩn dữ liệu thay vì xóa cứng vật lý, giúp bảo toàn tính toàn vẹn và dễ dàng khôi phục dữ liệu khi cần.
3. **Authentication (Đăng nhập phân quyền):** Cơ chế xác thực thông qua Session để bảo vệ các trang quản trị nội bộ, phân quyền rõ ràng giữa Admin và Staff.
