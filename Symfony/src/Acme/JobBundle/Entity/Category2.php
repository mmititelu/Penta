<?php
//namespace Acme\JobBundle\Entity;
//use Doctrine\ORM\Mapping as ORM;


/**
 * @orm\Entity
 * @ORM\Table(name="category")
 */
//class Category2
{
    /**
     * @orm\Id
     * @orm\Column(type="integer")
     * @orm\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @orm\Column(type="string", length=255, unique=true)
     */
    protected $name;
    private $more_jobs;
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    public function getSlug()
    {
        return Jobeet::slugify($this->getName());
    }
    public function setMoreJobs($jobs)
    {
        $this->more_jobs = $jobs >=  0 ? $jobs : 0;
    }
 
    public function getMoreJobs()
    {
        return $this->more_jobs;
    }
}