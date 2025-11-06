// Custom command login
Cypress.Commands.add('login', (url, username, password) => {
  cy.visit(url);
  cy.get('input[name="name"]').type(username);
  cy.get('input[name="pass"]').type(password);
  cy.get('form#user-login-form').submit();
});

// Custom command logout
Cypress.Commands.add('logout', (url) => {
  cy.visit(url);
  cy.get('form#user-login-form').submit();
});
