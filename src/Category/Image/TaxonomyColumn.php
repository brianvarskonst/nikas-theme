<?php


namespace Brianvarskonst\Nikas\Category\Image;


class TaxonomyColumn
{
    private CategoryImageUrlProvider $imageProvider;

    public function __construct(CategoryImageUrlProvider $imageProvider)
    {
        $this->imageProvider = $imageProvider;
    }

    public function register($columns)
    {
        return array_merge(
            $columns,
            [
                'thumb' => __('Image', 'nikas')
            ]
        );
    }

    public function render($columns, $column, $id)
    {
        if ($column === 'thumb') {
            $columns = '
                <span>
                    <img src="' . $this->imageProvider->provide($id, 'thumbnail') . '" alt="' . __('Thumbnail', 'nikas') . '" class="wp-post-image" />
                </span>
            ';
        }

        return $columns;
    }

    public function renderQuickEdit($column_name, $screen, $name)
    {
        if ($column_name === 'thumb')
            echo '
                <fieldset>
                    <div class="thumb inline-edit-col">
                        <label>
                            <span class="title">
                                <img src="" alt="Thumbnail"/>
                            </span>
                            
                            <span class="input-text-wrap">
                                <input type="text" name="zci_taxonomy_image" value="" class="tax_list" />
                            </span>
                            
                            <span class="input-text-wrap">
                                <button class="z_upload_image_button button">' . __('Upload/Add image', 'categories-images') . '</button>
                                <button class="z_remove_image_button button">' . __('Remove image', 'categories-images') . '</button>
                            </span>
                        </label>
                    </div>
                </fieldset>
            ';
    }

    public function save($term_id)
    {
        if (isset($_POST['zci_taxonomy_image'])) {
            update_option('z_taxonomy_image' . $term_id, $_POST['zci_taxonomy_image'], false);
        }
    }
}