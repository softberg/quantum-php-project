<li>
    <a class="search-tab">
        <form action="<?php echo base_url(true) . '/' . current_lang() ?>/posts" method="get" class="form-search">
            <input class="search-bar"
                   name="q" type="search"
                   placeholder="Search"
                   autocomplete="off"
                   value="<?php echo !empty($search_text) ? $search_text : '' ?>"
                <?php echo !empty($search_text) ? 'autofocus' : '' ?>>
        </form>
        <i class="material-icons search-bar-img"></i>
    </a>
</li>