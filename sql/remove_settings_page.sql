-- Settings is now a static page shown to every signed-in user, so it no longer belongs in the role-gated `pages` list.
-- Removes it from the Roles checkboxes. role_pages rows go with it (ON DELETE CASCADE). Safe to run more than once.

DELETE FROM pages WHERE page_key = 'settings';
