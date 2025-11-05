<?php

// Khai báo namespace của form, giúp Drupal định vị đúng class
namespace Drupal\student_manager\Form;

// Import các class cần thiết từ Drupal Core
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class StudentSearchForm
 * Tạo form tìm kiếm học sinh theo tên.
 */
class StudentSearchForm extends FormBase {

  /**
   * Hàm trả về ID định danh của form.
   * Drupal dùng ID này để quản lý form.
   */
  public function getFormId() {
    return 'student_search_form';
  }

  /**
   * Hàm dựng giao diện form.
   * Gồm một ô nhập tên và nút tìm kiếm.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Trường nhập tên để tìm kiếm
    $form['name'] = [
      '#type' => 'textfield',                          // Loại trường: nhập văn bản
      '#title' => 'Tìm theo tên',                      // Nhãn hiển thị
      '#default_value' => \Drupal::request()->query->get('name'), // Giá trị mặc định lấy từ URL (?name=...)
    ];

    // Nút submit để gửi form
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Tìm kiếm',
    ];

    return $form;
  }

  /**
   * Hàm xử lý khi form được submit.
   * Chuyển hướng đến trang danh sách với tham số tìm kiếm.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Lấy giá trị người dùng nhập
    $name = $form_state->getValue('name');

    // Chuyển hướng đến route student_manager.list kèm theo query ?name=...
    $form_state->setRedirect('student_manager.list', [], ['query' => ['name' => $name]]);
  }
}
