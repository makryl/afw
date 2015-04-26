<?php

namespace aeqdev\afw\controller;

class Sitemap
{

    protected $file;


    public function __construct($filename = null)
    {
        if (isset($filename)) {
            $this->file = fopen($filename, 'w');
            fwrite($this->file, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n");
            fwrite($this->file, "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n");
        } else {
            header('Content-Type: application/xml; charset=UTF-8');
            echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
            echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
        }
    }


    function close()
    {
        if (isset($this->file)) {
            fwrite($this->file, '</urlset>');
            fclose($this->file);
        } else {
            echo '</urlset>';
        }
    }


    function add($location, $lastmod = null, $changefreq = null, $priority = null)
    {
        if (isset($this->file)) {
            fwrite($this->file, '<url><loc>' . htmlspecialchars($location) . '</loc>');
            if (isset($lastmod)) {
                fwrite($this->file, '<lastmod>' . $lastmod . '</lastmod>');
            }
            if (isset($changefreq)) {
                fwrite($this->file, '<changefreq>' . $changefreq . '</changefreq>');
            }
            if (isset($priority)) {
                fwrite($this->file, '<priority>' . $priority . '</priority>');
            }
            fwrite($this->file, "</url>\n");
        } else {
            echo '<url><loc>', htmlspecialchars($location), '</loc>';
            if (isset($lastmod)) {
                echo '<lastmod>', $lastmod, '</lastmod>';
            }
            if (isset($changefreq)) {
                echo '<changefreq>', $changefreq, '</changefreq>';
            }
            if (isset($priority)) {
                echo '<priority>', $priority, '</priority>';
            }
            echo "</url>\n";
        }
    }


    function addStatement(\aeqdev\APDO\Statement $statement, $callback)
    {
        $pdo = $statement->options()->apdo->pdo();
        $st = $pdo->prepare($statement->buildSelect());
        $st->execute($statement->args());
        while ($row = $st->fetch(\PDO::FETCH_ASSOC)) {
            $params = $callback($row);
            if (!empty($params)) {
                $this->add($params[0], @$params[1], @$params[2], @$params[3]);
            }
        }
        $st->closeCursor();
    }

}
