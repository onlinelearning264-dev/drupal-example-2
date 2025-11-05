<?php

namespace Drupal\student_manager\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

class StudentForm extends FormBase {

  /**
   * ID định danh của form.
   */
  public function getFormId() {
    return 'student_form';
  }

  /**
   * Hàm dựng giao diện form thêm/sửa học sinh.
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    $record = [];

    // Nếu có ID (sửa), lấy dữ liệu học sinh từ DB
    if ($id) {
      $record = Database::getConnection()->select('student', 's')
        ->fields('s')
        ->condition('id', $id)
        ->execute()
        ->fetchAssoc();
    }

    // Trường ẩn chứa ID (dùng khi sửa)
    $form['id'] = [
      '#type' => 'hidden',
      '#value' => $record['id'] ?? '',
    ];

    // Trường nhập tên học sinh
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Tên học sinh'),
      '#default_value' => $record['name'] ?? '',
      '#required' => TRUE,
    ];

    // Trường nhập tuổi
    $form['age'] = [
      '#type' => 'number',
      '#title' => $this->t('Tuổi'),
      '#default_value' => $record['age'] ?? '',
      '#required' => TRUE,
    ];

    // Trường nhập lớp
    $form['class'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Lớp'),
      '#default_value' => $record['class'] ?? '',
      '#required' => TRUE,
    ];

    // Nút submit
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $id ? $this->t('Cập nhật') : $this->t('Thêm mới'),
    ];

    return $form;
  }

  /**
   * Hàm xử lý khi submit form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Lấy dữ liệu từ form
    $fields = [
      'name' => $form_state->getValue('name'),
      'age' => $form_state->getValue('age'),
      'class' => $form_state->getValue('class'),
    ];
    $id = $form_state->getValue('id');

    $conn = Database::getConnection();

    // Nếu có ID → cập nhật, ngược lại → thêm mới
    if ($id) {
      $conn->update('student')->fields($fields)->condition('id', $id)->execute();
      $this->messenger()->addMessage($this->t('Đã cập nhật học sinh.'));
    } else {
      $conn->insert('student')->fields($fields)->execute();
      $this->messenger()->addMessage($this->t('Đã thêm học sinh mới.'));
    }

    // Chuyển hướng về danh sách
    $form_state->setRedirect('student_manager.list');
  }
}
