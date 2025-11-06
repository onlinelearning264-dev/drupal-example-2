describe('Drupal Admin Login', () => {

  // Load dữ liệu từ fixtures trước khi chạy test
  let domain;
  before(() => {
    cy.fixture('domain').then((data) => {
      domain = data;
    });
  });

  let admin;
  before(() => {
    cy.fixture('admin').then((data) => {
      admin = data;
    });
  });

  // Test case 1: Đăng nhập thành công
  it('Đăng nhập thành công với tài khoản admin hợp lệ', () => {
    cy.login(domain.url, admin.username, admin.password); // dùng custom command
    cy.url().should('include', '/user/1');    // kiểm tra URL sau login
    cy.contains('Đăng xuất').should('be.visible');
    // cy.contains('Log out').should('be.visible');
  });

//   // Test case 2: Sai mật khẩu
//   it('Đăng nhập thất bại khi mật khẩu sai', () => {
//     cy.login(admin.username, 'sai_mat_khau');
//     cy.contains('Unrecognized username or password').should('be.visible');
//   });

//   // Test case 3: Sai username
//   it('Đăng nhập thất bại khi username sai', () => {
//     cy.login('wronguser', admin.password);
//     cy.contains('Unrecognized username or password').should('be.visible');
//   });

//   // Test case 4: Bỏ trống username và password
//   it('Hiển thị lỗi khi bỏ trống username và password', () => {
//     cy.visit(domain.url);
//     cy.get('form#user-login-form').submit();
//     cy.contains('Username field is required').should('be.visible');
//     cy.contains('Password field is required').should('be.visible');
//   });

//   // Test case 5: XSS test
//   it('Không cho phép script injection trong username', () => {
//     cy.login('<script>alert("hack")</script>', admin.password);
//     cy.contains('Unrecognized username or password').should('be.visible');
//   });

});
