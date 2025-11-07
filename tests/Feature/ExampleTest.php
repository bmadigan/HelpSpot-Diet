<?php

test('the application redirects to tickets', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('tickets.index'));
});
