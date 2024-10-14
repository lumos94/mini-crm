<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\Http\Requests\TransactionRequest;

class TransactionController extends Controller
{
    /**
     * Return the transactions view .
     */
    public function index()
    {
        return view('transactions');
    }

    /**
     * Get All Transactions
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTransactions()
    {
        // Return paginated transaction data for Vue.js as JSON
        $transactions = Transaction::with('client')->paginate(10);
        return response()->json($transactions);
    }

    /**
     * Store a newly created transaction in storage.
     *
     * @param \App\Http\Requests\TransactionRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TransactionRequest $request)
    {
        // Create a new transaction using the validated data
        $transaction = Transaction::create($request->validated());

        return response()->json($transaction, 201);
    }

    /**
     * Display the specified transaction.
     *
     * sos: frontend doesn't have an option for showing only one specific transaction
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        //Find the transaction that needs to be showed
        $transaction = Transaction::findOrFail($id);

        // Retrieve the transaction with the related client
        return response()->json($transaction->load('client'));
    }

    /**
     * Update the specified transaction in storage.
     *
     * @param \App\Http\Requests\TransactionRequest $request
     * @param                                       $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(TransactionRequest $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        // Update the transaction with the validated data
        $transaction->update($request->validated());

        return response()->json($transaction);
    }

    /**
     * Remove the specified transaction from storage.
     *
     * @param Transaction $transaction
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        //Find the transaction that needs to be deleted
        $transaction = Transaction::findOrFail($id);

        // Delete the transaction
        $transaction->delete();

        return response()->json(['message' => 'Transaction deleted successfully'], 200);

    }
}
