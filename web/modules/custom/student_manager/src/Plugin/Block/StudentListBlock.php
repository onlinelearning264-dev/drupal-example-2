<?php

namespace Drupal\student_manager\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * Tạo block hiển thị danh sách học sinh.
 *
 * @Block(
 *   id = "student_list_block",
 *   admin_label = @Translation("Danh sách học sinh"),
 *   category = @Translation("Custom")
 * )
 */
class StudentListBlock extends BlockBase {

  /**
   * Hàm build nội dung block.
   */
  public function build() {
    // Kết nối database
    $db = Database::getConnection();

    // Truy vấn lấy danh sách học sinh (giới hạn 5 dòng)
    $query = $db->select('student', 's')
      ->fields('s', ['id', 'name', 'age', 'class'])
      ->range(0, 5);
    $results = $query->execute();

    // Tạo bảng hiển thị
    $header = ['Tên', 'Tuổi', 'Lớp'];
    $rows = [];

    foreach ($results as $row) {
      $rows[] = [$row->name, $row->age, $row->class];
    }

    // Trả về render array
    return [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => $this->t('Không có học sinh nào.'),
      '#cache' => ['max-age' => 0], // Không cache để luôn hiển thị dữ liệu mới
    ];
  }
}
