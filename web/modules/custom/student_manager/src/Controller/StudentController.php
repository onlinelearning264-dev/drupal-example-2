<?php

namespace Drupal\student_manager\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Symfony\Component\HttpFoundation\RedirectResponse;

class StudentController extends ControllerBase {

  /**
   * Hàm hiển thị danh sách học sinh với phân trang và tìm kiếm.
   */
  public function list() {
    // Khai báo tiêu đề các cột trong bảng
    $header = [
      ['data' => $this->t('ID')],
      ['data' => $this->t('Tên')],
      ['data' => $this->t('Tuổi')],
      ['data' => $this->t('Lớp')],
      ['data' => $this->t('Hành động')],
    ];

    // Lấy từ khóa tìm kiếm từ URL (?name=...)
    $query = \Drupal::request()->query->get('name');

    // Kết nối đến database
    $db = Database::getConnection();

    // Tạo truy vấn SELECT từ bảng student
    $select = $db->select('student', 's')
      ->fields('s', ['id', 'name', 'age', 'class']);

    // Nếu có từ khóa tìm kiếm, thêm điều kiện LIKE
    if ($query) {
      $select->condition('name', '%' . $query . '%', 'LIKE');
    }

    // Thêm phân trang: mỗi trang hiển thị 10 dòng
    $pager = $select->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit(10);
    $results = $pager->execute();

    // Tạo mảng các dòng dữ liệu để hiển thị
    $rows = [];
    foreach ($results as $row) {
        // Tạo đường dẫn sửa và xóa
        $edit_url = Url::fromRoute('student_manager.edit', ['id' => $row->id]);
        $edit_link = [
            '#type' => 'link',
            '#url' => $edit_url,
            '#title' => $this->t('Sửa 1'),
        ];

        // $edit_link = Link::fromTextAndUrl(t('Sửa 1'), $edit_url);
        // $edit_link = $edit_link->toRenderable();
        
        $delete_url = Url::fromRoute('student_manager.delete', ['id' => $row->id]);
        $delete_link = [
            '#type' => 'link',
            '#url' => $delete_url,
            '#title' => $this->t('Sửa 1'),
        ];
        // $delete_link = Link::fromTextAndUrl(t('Xóa 1'), $delete_url);
        // $delete_link = $delete_link->toRenderable();

        // Tạo dòng hiển thị
        $rows[] = [
            $row->id,
            $row->name,
            $row->age,
            $row->class,
            // Tạo liên kết "Sửa" và "Xóa"
            $edit_link . ' | ' .
            $delete_link,
        ];
    }

    // Gọi form tìm kiếm
    $build['form'] = \Drupal::formBuilder()->getForm('Drupal\student_manager\Form\StudentSearchForm');

    // Tạo bảng hiển thị danh sách học sinh
    $build['table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => $this->t('Không có học sinh nào.'),
    ];

    // Thêm phân trang
    $build['pager'] = ['#type' => 'pager'];

    return $build;
  }

  /**
   * Hàm xử lý xóa học sinh theo ID.
   */
  public function delete($id) {
    // Xóa dòng có id tương ứng
    Database::getConnection()->delete('student')->condition('id', $id)->execute();

    // Hiển thị thông báo
    $this->messenger()->addMessage($this->t('Đã xóa học sinh.'));

    // Chuyển hướng về trang danh sách
    return new RedirectResponse(Url::fromRoute('student_manager.list')->toString());
  }
}
