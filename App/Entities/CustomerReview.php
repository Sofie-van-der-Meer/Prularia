<?php
declare(strict_types=1);

namespace App\Entities;

class CustomerReview {
    private int $reviewId;
    private string $nickname;
    private int $score;
    private ?string $comment;
    private \DateTime $date;
    private OrderLine $orderLine;
    
    public function __construct(
        int $reviewId = 0,
        string $nickname = "",
        int $score = 0,
        ?string $comment = null,
        \DateTime $date = null,
        OrderLine $orderLine
    ) {
        $this->reviewId = $reviewId;
        $this->nickname = $nickname;
        $this->score = $this->validateScore($score);
        $this->comment = $comment;
        $this->date = new \DateTime();
        $this->orderLine = new OrderLine();
        
    }

    // Getters and Setters
    public function getReviewId(): int {
        return $this->reviewId;
    }

    public function setReviewId(int $id): void {
        $this->reviewId = $id;
    }

    public function getNickname(): string {
        return $this->nickname;
    }

    public function setNickname(string $nickname): void {
        $this->nickname = $nickname;
    }

    public function getScore(): int {
        return $this->score;
    }

    public function setScore(int $score): void {
        $this->score = $this->validateScore($score);
    }

    public function getComment(): ?string {
        return $this->comment;
    }

    public function setComment(?string $comment): void {
        $this->comment = $comment;
    }

    public function getDate(): \DateTime {
        return $this->date;
    }

    public function setDate(\DateTime $date): void {
        $this->date = $date;
    }

    public function getOrderLine(): OrderLine {
        return $this->orderLine;
    }

    public function setOrderLine(OrderLine $orderLine): void {
        $this->orderLine = $orderLine;
    }

    // Helper methods
    private function validateScore(int $score): int {
        if ($score <= 1) {
            return 1;
        }
        if ($score >= 5) {
            return 5;
        }
        return $score;
    }

    public function hasComment(): bool {
        return $this->comment !== null && trim($this->comment) !== '';
    }

    public function getFormattedDate(string $format = 'Y-m-d'): string {
        return $this->date->format($format);
    }
}