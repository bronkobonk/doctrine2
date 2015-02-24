<?php

namespace Doctrine\Tests\ORM\Query;

use Doctrine\DBAL\Types\Type as DBALType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadataFactory;
use Doctrine\ORM\Query;

require_once __DIR__ . '/../../TestInit.php';

class SelectSqlInheritanceClassMetadataTest extends \Doctrine\Tests\OrmTestCase
{
    /**
     * @var EntityManager
     */
    private $_em;

    protected function setUp()
    {
        $this->_em = $this->_getTestEntityManager();
    }

    /**
     * Assert a valid SQL generation.
     *
     * @param string $dqlToBeTested
     * @param string $sqlToBeConfirmed
     * @param array $queryHints
     * @param array $queryParams
     */
    public function assertSqlGeneration($dqlToBeTested, $sqlToBeConfirmed, array $queryHints = array(), array $queryParams = array())
    {
        try {
            $query = $this->_em->createQuery($dqlToBeTested);

            foreach ($queryParams AS $name => $value) {
                $query->setParameter($name, $value);
            }

            $query->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
                ->useQueryCache(false);

            foreach ($queryHints AS $name => $value) {
                $query->setHint($name, $value);
            }

            $sqlGenerated = $query->getSQL();

            parent::assertEquals(
                $sqlToBeConfirmed,
                $sqlGenerated,
                sprintf('"%s" is not equal of "%s"', $sqlGenerated, $sqlToBeConfirmed)
            );

            $query->free();
        } catch (\Exception $e) {
            $this->fail($e->getMessage() ."\n".$e->getTraceAsString());
        }
    }

    public function testSupportsJoinOnMultipleComponentsWithJoinedInheritanceType2()
    {
        $cmf = new ClassMetadataFactory();
        $driver = $this->createAnnotationDriver(array(__DIR__ . '/../../Models/JoinedInheritanceTypeClassMetadata/'));
        
        $this->_em->getMetadataFactory()->
        
        $this->assertSqlGeneration(
            'SELECT t FROM Doctrine\Tests\Models\JoinedInheritanceTypeClassMetadata\CompanyTopic t JOIN t.group g WHERE g.productId = 1',
            'SELECT c0_.id AS id0, c0_.name AS name1, c1_.salary AS salary2, c1_.department AS department3, c1_.startDate AS startDate4, c0_.discr AS discr5 FROM company_employees c1_ INNER JOIN company_persons c0_ ON c1_.id = c0_.id INNER JOIN company_managers c2_ INNER JOIN company_employees c4_ ON c2_.id = c4_.id INNER JOIN company_persons c3_ ON c2_.id = c3_.id AND (c0_.id = c3_.id)'
        );
    }
}

class MyAbsFunction extends \Doctrine\ORM\Query\AST\Functions\FunctionNode
{
    public $simpleArithmeticExpression;

    /**
     * @override
     */
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return 'ABS(' . $sqlWalker->walkSimpleArithmeticExpression($this->simpleArithmeticExpression) . ')';
    }

    /**
     * @override
     */
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $lexer = $parser->getLexer();

        $parser->match(\Doctrine\ORM\Query\Lexer::T_IDENTIFIER);
        $parser->match(\Doctrine\ORM\Query\Lexer::T_OPEN_PARENTHESIS);

        $this->simpleArithmeticExpression = $parser->SimpleArithmeticExpression();

        $parser->match(\Doctrine\ORM\Query\Lexer::T_CLOSE_PARENTHESIS);
    }
}
/**
 * @Entity
 */
class DDC1384Model
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $aVeryLongIdentifierThatShouldBeShortenedByTheSQLWalker_fooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo;
}


/**
 * @Entity
 */
class DDC1474Entity
{

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue()
     */
    protected $id;

    /**
     * @column(type="float")
     */
    private $value;

    /**
     * @param string $float
     */
    public function __construct($float)
    {
        $this->value = $float;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param float $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

}

