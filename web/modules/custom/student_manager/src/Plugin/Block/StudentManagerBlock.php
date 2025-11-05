<?php

// Khai báo namespace để Drupal định vị đúng plugin block
namespace Drupal\student_manager\Plugin\Block;

// Import các class cần thiết từ Drupal Core
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * Định nghĩa block hiển thị danh sách học sinh.
 *
 * @Block(
 *   id = "student_manager_block",
 *   admin_label = @Translation("Student Manager Block"),
 *   category = @Translation("Custom")
 * )
 */
// ID nội bộ của block
// Tên hiển thị trong admin UI
// Nhóm hiển thị trong Block layout
class StudentManagerBlock extends BlockBase {

  /**
   * Hàm build() trả về nội dung hiển thị của block.
   */
  public function build() {
    $build = [];

    // Lấy từ khóa tìm kiếm từ URL (?name=...)
    $search = \Drupal::request()->query->get('name');

    // Kết nối đến database
    $db = Database::getConnection();

    // Tạo truy vấn SELECT từ bảng student
    $query = $db->select('student', 's')
      ->fields('s', ['id', 'name', 'age', 'class']);

    // Nếu có từ khóa tìm kiếm, thêm điều kiện LIKE
    if ($search) {
      $query->condition('name', '%' . $search . '%', 'LIKE');
    }

    // Thực thi truy vấn
    $results = $query->execute();

    // Tạo tiêu đề bảng
    $header = ['Tên', 'Tuổi', 'Lớp', 'Hành động'];
    $rows = [];

    // Duyệt qua kết quả và tạo từng dòng bảng
    foreach ($results as $row) {
      // Tạo đường dẫn sửa và xóa
      $edit_url = Url::fromRoute('student_manager.edit', ['id' => $row->id]);
      $delete_url = Url::fromRoute('student_manager.delete', ['id' => $row->id]);

      // Tạo dòng hiển thị
      $rows[] = [
        $row->name,
        $row->age,
        $row->class,
        [
            '#markup' => Link::fromTextAndUrl('Sửa 2', $edit_url)->toString() . ' | ' .
                        Link::fromTextAndUrl('Xóa 2', $delete_url)->toString(),
        ],
      ];
    }

    // Gọi form tìm kiếm (đã tạo ở StudentSearchForm.php)
    $build['search_form'] = \Drupal::formBuilder()->getForm('Drupal\student_manager\Form\StudentSearchForm');

    // Tạo bảng hiển thị danh sách học sinh
    $build['table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => $this->t('Không có học sinh nào.'),
    ];

    // Tạo liên kết "Thêm học sinh mới"
    $add_url = Url::fromRoute('student_manager.add');
    $build['add_link'] = [
      '#markup' => Link::fromTextAndUrl('➕ Thêm học sinh mới', $add_url)->toString(),
    ];

    // Trả về render array để Drupal hiển thị block
    return $build;
  }
}
