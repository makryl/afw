<?php

namespace afw;

require __DIR__ . '/../../afw/APDO.php';
require __DIR__ . '/../../afw/ICache.php';
require __DIR__ . '/../../afw/ArrayCache.php';



class APDOTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var APDO
     */
    protected $object;
    /**
     * @var AAPC
     */
    protected $cache;



    protected function setUp()
    {
        $this->object = new APDO('mysql:host=localhost;dbname=test', 'root', 'root', [
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "utf8"',
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ]);
        $this->object->execute('DROP TABLE IF EXISTS apdo_test');
        $this->object->execute('CREATE TABLE apdo_test (id int NOT NULL PRIMARY KEY)');
        $this->object->execute('INSERT INTO apdo_test (id) VALUES (1)');
        $this->object->execute('INSERT INTO apdo_test (id) VALUES (2)');
        $this->object->execute('INSERT INTO apdo_test (id) VALUES (3)');

        $this->cache = new ArrayCache();
    }



    protected function tearDown()
    {
        if (isset($this->object))
        {
            $this->object->execute('DROP TABLE IF EXISTS apdo_test');
        }
    }



    function testQueryCount()
    {
        $old_queryCount = $this->object->queryCount();
        $this->object->execute('SELECT 1');
        $this->object->select('SELECT 2');
        $this->object->selectFirst('SELECT 3');
        $this->assertEquals(3, $this->object->queryCount() - $old_queryCount);
    }



    function testLastQuery()
    {
        $this->object->execute('SELECT 111');
        $this->assertEquals('SELECT 111', $this->object->lastQuery());

        $this->object->select('SELECT 222');
        $this->assertEquals('SELECT 222', $this->object->lastQuery());

        $this->object->selectFirst('SELECT 333');
        $this->assertEquals('SELECT 333', $this->object->lastQuery());
    }



    function testExecute()
    {
        $errmode = $this->object->getAttribute(\PDO::ATTR_ERRMODE);
        $this->object->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_SILENT);

        $r = $this->object->execute('INSERT INTO apdo_test (id) VALUES (123)');
        $this->assertEquals(true, $r);

        $r = $this->object->execute('INSERT INTO apdo_test (id) VALUES (123)');
        $this->assertEquals(false, $r);

        $this->object->setAttribute(\PDO::ATTR_ERRMODE, $errmode);
    }



    function testSelectFirst()
    {
        $r = $this->object->selectFirst('SELECT * FROM apdo_test ORDER BY id');
        $this->assertEquals(['id' => 1], $r);
    }



    function testSelectFirstL()
    {
        $r = $this->object->selectFirstL('SELECT * FROM apdo_test ORDER BY id');
        $this->assertEquals([0 => 1], $r);
    }



    function testSelect()
    {
        $r = $this->object->select('SELECT * FROM apdo_test ORDER BY id LIMIT 1');
        $this->assertEquals([0 => ['id' => 1]], $r);
    }



    function testCache()
    {
        $statement = 'SELECT * FROM apdo_test ORDER BY id LIMIT 1';
        $result = [0 => ['id' => 1]];

        $r = $this->object
            ->cache($this->cache)
            ->select($statement);
        $this->object->reset();
        $this->assertEquals($result, $r);


        $queryCount = $this->object->queryCount();

        $r = $this->object
            ->cache($this->cache)
            ->select($statement);
        $this->object->reset();
        $this->assertEquals($result, $r);
        $this->assertEquals($queryCount, $this->object->queryCount());

    }



    function testSelectK()
    {
        $r = $this->object->selectK('SELECT id, id FROM apdo_test ORDER BY id LIMIT 1');
        $this->assertEquals([1 => 1], $r);
    }



    function testFrom()
    {
        $r = $this->object
            ->from('apdo_test')
            ->orderBy('id')
            ->first();
        $this->assertEquals(
            'SELECT *
FROM apdo_test
ORDER BY id
LIMIT 0, 1', $this->object->lastQuery());
        $this->assertEquals(['id' => 1], $r);
    }



    function testIn()
    {
        $r = $this->object
            ->in('apdo_test')
            ->orderBy('id')
            ->first();
        $this->assertEquals(
            'SELECT *
FROM apdo_test
ORDER BY id
LIMIT 0, 1', $this->object->lastQuery());
        $this->assertEquals(['id' => 1], $r);
    }



    function testPkey()
    {
        $r = $this->object
            ->from('apdo_test')
            ->pkey('id')
            ->key(1)
            ->first();
        $this->assertEquals(
            'SELECT *
FROM apdo_test
WHERE id=?
LIMIT 0, 1', $this->object->lastQuery());
        $this->assertEquals(['id' => 1], $r);
    }



    function testJoin()
    {
        $r = $this->object
            ->from('apdo_test A')
            ->join('apdo_test B', 'A.id+1=B.id')
            ->orderBy('A.id')
            ->first();
        $this->assertEquals(
            'SELECT *
FROM apdo_test A
 JOIN apdo_test B ON (A.id+1=B.id)
ORDER BY A.id
LIMIT 0, 1',
            $this->object->lastQuery());
        $this->assertEquals(['id' => 2], $r);
    }



    function testWhere()
    {
        $r = $this->object
            ->from('apdo_test')
            ->where('id=?', 2)
            ->first();
        $this->assertEquals(
            'SELECT *
FROM apdo_test
WHERE id=?
LIMIT 0, 1', $this->object->lastQuery());
        $this->assertEquals(['id' => 2], $r);
    }



    function testOrWhere()
    {
        $r = $this->object
            ->from('apdo_test')
            ->where('id=?', 2)
            ->orWhere('id=?', 3)
            ->all();
        $this->assertEquals(
            'SELECT *
FROM apdo_test
WHERE (id=?) OR (id=?)', $this->object->lastQuery());
        $this->assertEquals([['id' => 2], ['id' => 3]], $r);
    }



    function testKey()
    {
        $r = $this->object
            ->from('apdo_test')
            ->key([2, 3])
            ->first();
        $this->assertEquals(
            'SELECT *
FROM apdo_test
WHERE id IN (?,?)
LIMIT 0, 1', $this->object->lastQuery());
        $this->assertEquals(['id' => 2], $r);
    }



    function testOrKey()
    {
        $r = $this->object
            ->from('apdo_test')
            ->key([2, 3])
            ->orKey([1, 2])
            ->all();
        $this->assertEquals(
            'SELECT *
FROM apdo_test
WHERE (id IN (?,?)) OR (id IN (?,?))', $this->object->lastQuery());
        $this->assertEquals([['id' => 1], ['id' => 2], ['id' => 3]], $r);
    }



    function testOrderby()
    {
        $this->object->execute('INSERT INTO apdo_test (id) VALUES (999999)');
        $r = $this->object
            ->from('apdo_test')
            ->orderBy('id DESC')
            ->first();
        $this->assertEquals(
            'SELECT *
FROM apdo_test
ORDER BY id DESC
LIMIT 0, 1', $this->object->lastQuery());
        $this->assertEquals(['id' => 999999], $r);
    }



    function testLimit()
    {
        $r = $this->object
            ->from('apdo_test')
            ->limit(2)
            ->all();
        $this->assertEquals(
            'SELECT *
FROM apdo_test
LIMIT 0, 2', $this->object->lastQuery());
        $this->assertEquals(2, count($r));
    }



    function testOffset()
    {
        $r = $this->object
            ->from('apdo_test')
            ->orderBy('id')
            ->limit(1)
            ->offset(1)
            ->all();
        $this->assertEquals(
            'SELECT *
FROM apdo_test
ORDER BY id
LIMIT 1, 1', $this->object->lastQuery());
        $this->assertEquals([0 => ['id' => 2]], $r);
    }



    function testPage()
    {
        $r = $this->object
            ->from('apdo_test')
            ->orderBy('id')
            ->limit(1)
            ->page(2);
        $this->assertEquals(
            'SELECT *
FROM apdo_test
ORDER BY id
LIMIT 1, 1', $this->object->lastQuery());
        $this->assertEquals([0 => ['id' => 2]], $r);
    }



    function testFields()
    {
        $r = $this->object
            ->from('apdo_test')
            ->fields(['id', 'id AS id2'])
            ->orderBy('id')
            ->first();
        $this->assertEquals(
            'SELECT id, id AS id2
FROM apdo_test
ORDER BY id
LIMIT 0, 1', $this->object->lastQuery());
        $this->assertEquals(['id' => 1, 'id2' => 1], $r);
    }



    function testFirst()
    {
        $r = $this->object
            ->from('apdo_test')
            ->orderBy('id')
            ->first();
        $this->assertEquals(
            'SELECT *
FROM apdo_test
ORDER BY id
LIMIT 0, 1', $this->object->lastQuery());
        $this->assertEquals(['id' => 1], $r);
    }



    function testFirstL()
    {
        $r = $this->object
            ->from('apdo_test')
            ->orderBy('id')
            ->firstL();
        $this->assertEquals(
            'SELECT *
FROM apdo_test
ORDER BY id
LIMIT 0, 1', $this->object->lastQuery());
        $this->assertEquals([0 => 1], $r);
    }



    function testAll()
    {
        $r = $this->object
            ->from('apdo_test')
            ->orderBy('id')
            ->limit(1)
            ->all();
        $this->assertEquals(
            'SELECT *
FROM apdo_test
ORDER BY id
LIMIT 0, 1', $this->object->lastQuery());
        $this->assertEquals([0 => ['id' => 1]], $r);
    }



    function testAllK()
    {
        $r = $this->object
            ->from('apdo_test')
            ->fields('id, id')
            ->orderBy('id')
            ->limit(1)
            ->allK();
        $this->assertEquals(
            'SELECT id, id
FROM apdo_test
ORDER BY id
LIMIT 0, 1', $this->object->lastQuery());
        $this->assertEquals([1 => 1], $r);
    }



    function testKeys()
    {
        $r = $this->object
            ->from('apdo_test')
            ->orderBy('id')
            ->limit(1)
            ->keys('id');
        $this->assertEquals(
            'SELECT id, id
FROM apdo_test
ORDER BY id
LIMIT 0, 1', $this->object->lastQuery());
        $this->assertEquals([1 => 1], $r);
    }



    function testCount()
    {
        $r = $this->object
            ->from('apdo_test')
            ->key([1, 2])
            ->count();
        $this->assertEquals(
            'SELECT COUNT(*)
FROM apdo_test
WHERE id IN (?,?)', $this->object->lastQuery());
        $this->assertEquals(2, $r);
    }



    function testInsert()
    {
        $this->object
            ->in('apdo_test')
            ->insert([['id' => 11], ['id' => 12]]);
        $this->assertEquals(
            'INSERT INTO apdo_test (id) VALUES
(?),
(?)', $this->object->lastQuery());

        $r = $this->object
            ->from('apdo_test')
            ->key([11, 12])
            ->all();
        $this->assertEquals([['id' => 11], ['id' => 12]], $r);
    }



    function testUpdate()
    {
        $this->object
            ->in('apdo_test')
            ->insert(['id' => 21]);

        $this->object
            ->in('apdo_test')
            ->where('id=?', 21)
            ->update(['id' => 22]);

        $this->assertEquals(
            "UPDATE apdo_test
SET
\tid=?
WHERE id=?", $this->object->lastQuery());

        $r = $this->object
            ->from('apdo_test')
            ->key(22)
            ->first();
        $this->assertEquals(['id' => 22], $r);
    }



    function testDelete()
    {
        $this->object
            ->in('apdo_test')
            ->insert(['id' => 31]);

        $this->object
            ->from('apdo_test')
            ->where('id=?', 31)
            ->delete();

        $this->assertEquals(
            'DELETE FROM apdo_test
WHERE id=?', $this->object->lastQuery());

        $r = $this->object
            ->from('apdo_test')
            ->key(31)
            ->first();
        $this->assertEquals(false, $r);
    }



    function testReferences()
    {

    }



    function testReferers()
    {

    }

}

?>
