<?php

class pinImpFeedUpdater
{

    public function updateFeed($feedUrl)
    {
        $added = 0;

        $feed = new SimplePie();
        $feed->enable_cache(false);
        $feed->set_feed_url($feedUrl);
        $feed->init();
        $feed->handle_content_type();

        /** @var $item SimplePie_Item */
        foreach ($feed->get_items() as $item) {

            $dom = new DOMDocument();
            $dom->loadHTML($item->get_description());
            $imageTags = $dom->getElementsByTagName('img');

            $images = array();
            foreach($imageTags as $tag) {
                if ($imageUrl = $this->preprareImageUrl($tag->getAttribute('src'))) {
                    $images[] = $imageUrl;
                }
            }

            if (count($images) > 0) {
                $pintImageUrl = $images[0];

                $info = pathinfo($pintImageUrl);

                $this->saveToDatabase(
                    $item->get_id(),
                    $item->get_title(),
                    $info['basename'],
                    $pintImageUrl,
                    $feedUrl,
                    $item->get_date('Y-m-d H:i:s')
                );

                $added++;
            }
        }

        return $added;
    }

    protected function saveToDatabase($id, $title, $filename, $url, $feed, $datetime)
    {
        global $wpdb;

        $pinimp_images = $wpdb->prefix . 'pinimp_images';

        $result = $wpdb->query(
            "INSERT INTO $pinimp_images (id, title, filename, sourcefeed, imagedate, meta_data) VALUES "
            . "('$id', '$title', '$filename', '$feed', '$datetime', '$url');");
        $pid = (int) $wpdb->insert_id;
        //wp_cache_delete($pid, 'ngg_gallery');

        return false;
    }

    protected function preprareImageUrl($url)
    {

        return $url;
    }
}