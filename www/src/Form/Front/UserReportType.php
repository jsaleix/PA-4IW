<?php

namespace App\Form\Front;

use App\Entity\ReportReason;
use App\Entity\UserReport;
use App\Repository\ReportReasonRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserReportType extends AbstractType
{
    public function __construct(private ReportReasonRepository $reasonRepository)
    {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reason', EntityType::class, [
                'class' => ReportReason::class,
                'choices'=> $this->reasonRepository->findBy(['type' => 1]),
                'choice_label' => 'name',
                'multiple' => false
            ])
            ->add('Submit', SubmitType::class);

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserReport::class,
        ]);
    }
}
