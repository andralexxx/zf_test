INSERT INTO guestbook (email, firstname, lastname, phone, birthday, created) VALUES
    ('ralph.schindler@zend.com',
    'Ralph',
    'Schindler',
    '555-12-34-56',
	'12.05.1990',
    DATETIME('NOW'));
INSERT INTO guestbook (email, firstname, lastname, phone, birthday, created) VALUES
    ('foo@bar.com',
    'Foo',
    'Bar',
    'XXX-XX-XX-XX',
    'dd.mm.yyyy',
    DATETIME('NOW'));