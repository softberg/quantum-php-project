<?php

namespace Modules\Main\Models;

use Quantum\Mvc\Qt_Model;

class Post extends Qt_Model {

    private $posts = [
        '1' => [
            'title' => 'Lorem ipsum dolor sit amet',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque lacus lectus, ultricies eget nunc vel, scelerisque dapibus sem. Cras suscipit metus ante, eget imperdiet enim eleifend eget. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Phasellus congue diam non nisl ullamcorper, tempor egestas nulla tincidunt. Suspendisse eget turpis pretium, vehicula sem id, auctor dui.',
        ],
        '2' => [
            'title' => 'Etiam at lectus ut enim',
            'content' => 'Etiam at lectus ut enim sodales accumsan non et odio. Praesent egestas sed erat accumsan bibendum. Donec lobortis eu augue sed volutpat. Etiam cursus ante turpis, sit amet auctor arcu imperdiet vitae. Cras ullamcorper urna nulla, vel fermentum lacus gravida sed. Maecenas varius sed ligula quis varius. Nulla ut placerat massa. Praesent a mauris sollicitudin, pellentesque lectus nec, interdum mauris.',
        ],
        '3' => [
            'title' => 'Sed vel rhoncus mi',
            'content' => 'Sed vel rhoncus mi. Vestibulum malesuada, odio vitae porta hendrerit, sem nibh laoreet orci, sit amet imperdiet lorem est sed erat. Vestibulum vestibulum erat nec risus pulvinar, ac luctus nulla luctus. Cras rhoncus id elit sed posuere. Integer porta elit in congue ornare. Nulla dolor justo, suscipit feugiat facilisis sed, dictum non leo. Phasellus eleifend odio sit amet orci condimentum pulvinar.',
        ],
    ];

    public function getPosts() {
        return $this->posts;
    }

    public function getPost($id) {
        if (isset($this->posts[$id])) {
            return $this->posts[$id];
        }

        return NULL;
    }

    public function addPost($post) {
        array_push($this->posts, $post);
    }

    public function updatePost($id, $post) {
        $this->posts[$id] = $post;
    }

    public function deletePost($id) {
        unset($this->posts[$id]);
    }

}
