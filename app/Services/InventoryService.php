<?php

namespace App\Services;

use App\Exceptions\InsufficientInventoryException;
use App\Exceptions\InventoryException;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class InventoryService
{
    public function receive(
        int $productId,
        int $quantity,
        string $referenceType,
        int $referenceId,
        string $movementKey,
        ?int $userId = null,
        ?string $notes = null,
    ): InventoryTransaction {
        $this->validateQuantity($quantity);

        return DB::transaction(function () use (
            $productId,
            $quantity,
            $referenceType,
            $referenceId,
            $movementKey,
            $userId,
            $notes,
        ): InventoryTransaction {
            $existing = $this->existingMovement($movementKey);

            if ($existing) {
                return $existing;
            }

            $inventory = $this->lockedInventory($productId);
            $existing = $this->existingMovement($movementKey);

            if ($existing) {
                return $existing;
            }

            $stockBefore = $inventory->stock;

            $inventory->increment('stock', $quantity);
            $inventory->refresh();

            return $this->recordMovement(
                inventory: $inventory,
                movementType: 'in',
                quantity: $quantity,
                stockBefore: $stockBefore,
                committedBefore: $inventory->committed_quantity,
                referenceType: $referenceType,
                referenceId: $referenceId,
                movementKey: $movementKey,
                userId: $userId,
                notes: $notes,
            );
        });
    }

    public function issue(
        int $productId,
        int $quantity,
        string $referenceType,
        int $referenceId,
        string $movementKey,
        ?int $userId = null,
        ?string $notes = null,
    ): InventoryTransaction {
        $this->validateQuantity($quantity);

        return DB::transaction(function () use (
            $productId,
            $quantity,
            $referenceType,
            $referenceId,
            $movementKey,
            $userId,
            $notes,
        ): InventoryTransaction {
            $existing = $this->existingMovement($movementKey);

            if ($existing) {
                return $existing;
            }

            $inventory = $this->lockedInventory($productId);
            $existing = $this->existingMovement($movementKey);

            if ($existing) {
                return $existing;
            }

            if ($inventory->available_quantity < $quantity) {
                throw new InsufficientInventoryException(
                    "El producto {$productId} no tiene existencias disponibles suficientes."
                );
            }

            $stockBefore = $inventory->stock;
            $committedBefore = $inventory->committed_quantity;
            $inventory->decrement('stock', $quantity);
            $inventory->refresh();

            return $this->recordMovement(
                inventory: $inventory,
                movementType: 'out',
                quantity: $quantity,
                stockBefore: $stockBefore,
                committedBefore: $committedBefore,
                referenceType: $referenceType,
                referenceId: $referenceId,
                movementKey: $movementKey,
                userId: $userId,
                notes: $notes,
            );
        });
    }

    public function issueCommitted(
        int $productId,
        int $quantity,
        string $referenceType,
        int $referenceId,
        string $movementKey,
        ?int $userId = null,
        ?string $notes = null,
    ): InventoryTransaction {
        $this->validateQuantity($quantity);

        return DB::transaction(function () use (
            $productId,
            $quantity,
            $referenceType,
            $referenceId,
            $movementKey,
            $userId,
            $notes,
        ): InventoryTransaction {
            $existing = $this->existingMovement($movementKey);

            if ($existing) {
                return $existing;
            }

            $inventory = $this->lockedInventory($productId);
            $existing = $this->existingMovement($movementKey);

            if ($existing) {
                return $existing;
            }

            if ($inventory->stock < $quantity || $inventory->committed_quantity < $quantity) {
                throw new InsufficientInventoryException(
                    "El producto {$productId} no tiene suficientes unidades comprometidas para confirmar la salida."
                );
            }

            $stockBefore = $inventory->stock;
            $committedBefore = $inventory->committed_quantity;
            $inventory->decrement('stock', $quantity);
            $inventory->decrement('committed_quantity', $quantity);
            $inventory->refresh();

            return $this->recordMovement(
                inventory: $inventory,
                movementType: 'out_committed',
                quantity: $quantity,
                stockBefore: $stockBefore,
                committedBefore: $committedBefore,
                referenceType: $referenceType,
                referenceId: $referenceId,
                movementKey: $movementKey,
                userId: $userId,
                notes: $notes,
            );
        });
    }

    public function commit(
        int $productId,
        int $quantity,
        string $referenceType,
        int $referenceId,
        string $movementKey,
        ?int $userId = null,
        ?string $notes = null,
    ): InventoryTransaction {
        $this->validateQuantity($quantity);

        return DB::transaction(function () use (
            $productId,
            $quantity,
            $referenceType,
            $referenceId,
            $movementKey,
            $userId,
            $notes,
        ): InventoryTransaction {
            $existing = $this->existingMovement($movementKey);

            if ($existing) {
                return $existing;
            }

            $inventory = $this->lockedInventory($productId);
            $existing = $this->existingMovement($movementKey);

            if ($existing) {
                return $existing;
            }

            if ($inventory->available_quantity < $quantity) {
                throw new InsufficientInventoryException(
                    "El producto {$productId} no tiene disponibilidad suficiente para comprometerse."
                );
            }

            $stockBefore = $inventory->stock;
            $committedBefore = $inventory->committed_quantity;
            $inventory->increment('committed_quantity', $quantity);
            $inventory->refresh();

            return $this->recordMovement(
                inventory: $inventory,
                movementType: 'commitment',
                quantity: $quantity,
                stockBefore: $stockBefore,
                committedBefore: $committedBefore,
                referenceType: $referenceType,
                referenceId: $referenceId,
                movementKey: $movementKey,
                userId: $userId,
                notes: $notes,
            );
        });
    }

    public function releaseCommitment(
        int $productId,
        int $quantity,
        string $referenceType,
        int $referenceId,
        string $movementKey,
        ?int $userId = null,
        ?string $notes = null,
    ): InventoryTransaction {
        $this->validateQuantity($quantity);

        return DB::transaction(function () use (
            $productId,
            $quantity,
            $referenceType,
            $referenceId,
            $movementKey,
            $userId,
            $notes,
        ): InventoryTransaction {
            $existing = $this->existingMovement($movementKey);

            if ($existing) {
                return $existing;
            }

            $inventory = $this->lockedInventory($productId);
            $existing = $this->existingMovement($movementKey);

            if ($existing) {
                return $existing;
            }

            if ($inventory->committed_quantity < $quantity) {
                throw new InventoryException(
                    "El producto {$productId} no tiene suficientes unidades comprometidas para liberar."
                );
            }

            $stockBefore = $inventory->stock;
            $committedBefore = $inventory->committed_quantity;
            $inventory->decrement('committed_quantity', $quantity);
            $inventory->refresh();

            return $this->recordMovement(
                inventory: $inventory,
                movementType: 'commitment_release',
                quantity: $quantity,
                stockBefore: $stockBefore,
                committedBefore: $committedBefore,
                referenceType: $referenceType,
                referenceId: $referenceId,
                movementKey: $movementKey,
                userId: $userId,
                notes: $notes,
            );
        });
    }

    public function adjust(
        int $productId,
        int $quantity,
        string $referenceType,
        int $referenceId,
        string $movementKey,
        ?int $userId = null,
        ?string $notes = null,
    ): InventoryTransaction {
        if ($quantity === 0) {
            throw new InvalidArgumentException('La cantidad del ajuste no puede ser cero.');
        }

        return DB::transaction(function () use (
            $productId,
            $quantity,
            $referenceType,
            $referenceId,
            $movementKey,
            $userId,
            $notes,
        ): InventoryTransaction {
            $existing = $this->existingMovement($movementKey);

            if ($existing) {
                return $existing;
            }

            $inventory = $this->lockedInventory($productId);
            $existing = $this->existingMovement($movementKey);

            if ($existing) {
                return $existing;
            }
            $stockBefore = $inventory->stock;
            $committedBefore = $inventory->committed_quantity;
            $stockAfter = $stockBefore + $quantity;

            if ($stockAfter < $committedBefore) {
                throw new InsufficientInventoryException(
                    "El ajuste del producto {$productId} dejaría existencias disponibles negativas."
                );
            }

            $inventory->update(['stock' => $stockAfter]);

            return $this->recordMovement(
                inventory: $inventory,
                movementType: 'adjustment',
                quantity: $quantity,
                stockBefore: $stockBefore,
                committedBefore: $committedBefore,
                referenceType: $referenceType,
                referenceId: $referenceId,
                movementKey: $movementKey,
                userId: $userId,
                notes: $notes,
            );
        });
    }

    public function reverse(
        InventoryTransaction $transaction,
        string $movementKey,
        ?int $userId = null,
        ?string $notes = null,
    ): InventoryTransaction {
        return DB::transaction(function () use ($transaction, $movementKey, $userId, $notes): InventoryTransaction {
            $existing = $this->existingMovement($movementKey);

            if ($existing) {
                return $existing;
            }

            $original = InventoryTransaction::query()
                ->lockForUpdate()
                ->findOrFail($transaction->id);

            if (InventoryTransaction::query()->where('reversal_of_id', $original->id)->exists()) {
                throw new InventoryException('El movimiento ya fue revertido.');
            }

            return match ($original->movement_type) {
                'in' => $this->reverseReceive($original, $movementKey, $userId, $notes),
                'out' => $this->reverseIssue($original, $movementKey, $userId, $notes),
                'out_committed' => $this->reverseCommittedIssue($original, $movementKey, $userId, $notes),
                'commitment' => $this->reverseCommitment($original, $movementKey, $userId, $notes),
                'commitment_release' => $this->reverseCommitmentRelease($original, $movementKey, $userId, $notes),
                'adjustment' => $this->reverseAdjustment($original, $movementKey, $userId, $notes),
                default => throw new InventoryException('El tipo de movimiento no puede revertirse.'),
            };
        });
    }

    private function lockedInventory(int $productId): Inventory
    {
        Inventory::query()->firstOrCreate(
            ['product_id' => $productId],
            ['stock' => 0, 'committed_quantity' => 0, 'in_transit_quantity' => 0, 'low_stock_threshold' => 0],
        );

        return Inventory::query()
            ->where('product_id', $productId)
            ->lockForUpdate()
            ->firstOrFail();
    }

    private function existingMovement(string $movementKey): ?InventoryTransaction
    {
        return InventoryTransaction::query()
            ->where('movement_key', $movementKey)
            ->lockForUpdate()
            ->first();
    }

    private function recordMovement(
        Inventory $inventory,
        string $movementType,
        int $quantity,
        int $stockBefore,
        int $committedBefore,
        string $referenceType,
        int $referenceId,
        string $movementKey,
        ?int $userId,
        ?string $notes,
        ?int $reversalOfId = null,
    ): InventoryTransaction {
        return $inventory->transactions()->create([
            'product_id' => $inventory->product_id,
            'movement_type' => $movementType,
            'quantity' => $quantity,
            'stock_before' => $stockBefore,
            'stock_after' => $inventory->stock,
            'committed_before' => $committedBefore,
            'committed_after' => $inventory->committed_quantity,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'movement_key' => $movementKey,
            'reversal_of_id' => $reversalOfId,
            'user_id' => $userId ?? Auth::id(),
            'notes' => $notes,
        ]);
    }

    private function reverseReceive(
        InventoryTransaction $original,
        string $movementKey,
        ?int $userId,
        ?string $notes,
    ): InventoryTransaction {
        $inventory = $this->lockedInventory($original->product_id);
        $stockBefore = $inventory->stock;
        $committedBefore = $inventory->committed_quantity;

        if ($inventory->stock - $original->quantity < $committedBefore) {
            throw new InsufficientInventoryException('La reversión dejaría existencias disponibles negativas.');
        }

        $inventory->decrement('stock', $original->quantity);
        $inventory->refresh();

        return $this->recordMovement(
            $inventory,
            'reversal',
            -$original->quantity,
            $stockBefore,
            $committedBefore,
            $original->reference_type,
            $original->reference_id,
            $movementKey,
            $userId,
            $notes,
            $original->id,
        );
    }

    private function reverseIssue(
        InventoryTransaction $original,
        string $movementKey,
        ?int $userId,
        ?string $notes,
    ): InventoryTransaction {
        return $this->reverseStockChange($original, $original->quantity, $movementKey, $userId, $notes);
    }

    private function reverseCommittedIssue(
        InventoryTransaction $original,
        string $movementKey,
        ?int $userId,
        ?string $notes,
    ): InventoryTransaction {
        $inventory = $this->lockedInventory($original->product_id);
        $stockBefore = $inventory->stock;
        $committedBefore = $inventory->committed_quantity;
        $inventory->increment('stock', $original->quantity);
        $inventory->increment('committed_quantity', $original->quantity);
        $inventory->refresh();

        return $this->recordMovement(
            $inventory,
            'reversal',
            $original->quantity,
            $stockBefore,
            $committedBefore,
            $original->reference_type,
            $original->reference_id,
            $movementKey,
            $userId,
            $notes,
            $original->id,
        );
    }

    private function reverseAdjustment(
        InventoryTransaction $original,
        string $movementKey,
        ?int $userId,
        ?string $notes,
    ): InventoryTransaction {
        return $this->reverseStockChange($original, -$original->quantity, $movementKey, $userId, $notes);
    }

    private function reverseStockChange(
        InventoryTransaction $original,
        int $quantity,
        string $movementKey,
        ?int $userId,
        ?string $notes,
    ): InventoryTransaction {
        $inventory = $this->lockedInventory($original->product_id);
        $stockBefore = $inventory->stock;
        $committedBefore = $inventory->committed_quantity;
        $inventory->increment('stock', $quantity);
        $inventory->refresh();

        return $this->recordMovement(
            $inventory,
            'reversal',
            $quantity,
            $stockBefore,
            $committedBefore,
            $original->reference_type,
            $original->reference_id,
            $movementKey,
            $userId,
            $notes,
            $original->id,
        );
    }

    private function reverseCommitment(
        InventoryTransaction $original,
        string $movementKey,
        ?int $userId,
        ?string $notes,
    ): InventoryTransaction {
        return $this->reverseCommitmentChange($original, -$original->quantity, $movementKey, $userId, $notes);
    }

    private function reverseCommitmentRelease(
        InventoryTransaction $original,
        string $movementKey,
        ?int $userId,
        ?string $notes,
    ): InventoryTransaction {
        return $this->reverseCommitmentChange($original, $original->quantity, $movementKey, $userId, $notes);
    }

    private function reverseCommitmentChange(
        InventoryTransaction $original,
        int $quantity,
        string $movementKey,
        ?int $userId,
        ?string $notes,
    ): InventoryTransaction {
        $inventory = $this->lockedInventory($original->product_id);
        $stockBefore = $inventory->stock;
        $committedBefore = $inventory->committed_quantity;
        $committedAfter = $committedBefore + $quantity;

        if ($committedAfter < 0 || $committedAfter > $inventory->stock) {
            throw new InventoryException('La reversión dejaría inválidas las unidades comprometidas.');
        }

        $inventory->update(['committed_quantity' => $committedAfter]);

        return $this->recordMovement(
            $inventory,
            'reversal',
            $quantity,
            $stockBefore,
            $committedBefore,
            $original->reference_type,
            $original->reference_id,
            $movementKey,
            $userId,
            $notes,
            $original->id,
        );
    }

    private function validateQuantity(int $quantity): void
    {
        if ($quantity < 1) {
            throw new InvalidArgumentException('La cantidad debe ser un entero positivo.');
        }
    }
}
