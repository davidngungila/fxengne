<?php

namespace App\Http\Controllers;

use App\Models\MLModel;
use App\Services\MLModelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MLModelController extends Controller
{
    protected $mlModelService;

    public function __construct(MLModelService $mlModelService)
    {
        $this->mlModelService = $mlModelService;
    }

    /**
     * Display ML models dashboard
     */
    public function index()
    {
        $userId = Auth::id();
        
        $models = MLModel::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $activeModels = $models->where('is_active', true);
        $trainedModels = $models->where('status', 'trained');
        
        $stats = [
            'total' => $models->count(),
            'active' => $activeModels->count(),
            'trained' => $trainedModels->count(),
            'training' => $models->where('status', 'training')->count(),
        ];

        return view('ml-models.index', [
            'models' => $models,
            'stats' => $stats,
        ]);
    }

    /**
     * Show model creation form
     */
    public function create()
    {
        $modelTypes = [
            'price_direction' => 'Price Direction / Entry Signal',
            'volatility' => 'Volatility Forecasting',
            'sentiment' => 'Sentiment Analysis',
            'parameter_optimization' => 'Parameter Optimization',
        ];

        $architectures = [
            'TFT' => 'Temporal Fusion Transformer',
            'LSTM' => 'Long Short-Term Memory',
            'XGBoost' => 'XGBoost',
            'RandomForest' => 'Random Forest',
            'FinBERT' => 'FinBERT (NLP)',
            'Hybrid' => 'Hybrid Model',
            'GridSearch' => 'Grid Search',
        ];

        return view('ml-models.create', [
            'modelTypes' => $modelTypes,
            'architectures' => $architectures,
        ]);
    }

    /**
     * Store a new model
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:price_direction,volatility,sentiment,parameter_optimization',
            'architecture' => 'required|in:TFT,LSTM,XGBoost,RandomForest,FinBERT,Hybrid,GridSearch',
            'description' => 'nullable|string',
            'config' => 'nullable|array',
            'training_config' => 'nullable|array',
        ]);

        $model = $this->mlModelService->createModel(array_merge($validated, [
            'user_id' => Auth::id(),
            'status' => 'draft',
        ]));

        return redirect()->route('ml-models.show', $model)
            ->with('success', 'ML Model created successfully');
    }

    /**
     * Show model details
     */
    public function show(MLModel $mlModel)
    {
        if ($mlModel->user_id !== Auth::id()) {
            abort(403);
        }
        
        $recommendations = $this->mlModelService->getModelRecommendations($mlModel->type);
        $statistics = $this->mlModelService->getModelStatistics($mlModel);
        $recentPredictions = $mlModel->predictions()->latest()->limit(10)->get();
        $trainingLogs = $mlModel->trainingLogs()->latest()->get();

        return view('ml-models.show', [
            'model' => $mlModel,
            'recommendations' => $recommendations,
            'statistics' => $statistics,
            'recentPredictions' => $recentPredictions,
            'trainingLogs' => $trainingLogs,
        ]);
    }

    /**
     * Start training a model
     */
    public function startTraining(MLModel $mlModel)
    {
        if ($mlModel->user_id !== Auth::id()) {
            abort(403);
        }
        
        if ($mlModel->status !== 'draft' && $mlModel->status !== 'trained') {
            return back()->with('error', 'Model must be in draft or trained status to start training');
        }

        $this->mlModelService->startTraining($mlModel);

        // In a real implementation, you would queue a job here to actually train the model
        // For now, we'll just update the status

        return back()->with('success', 'Model training started');
    }

    /**
     * Toggle model active status
     */
    public function toggleActive(MLModel $mlModel)
    {
        if ($mlModel->user_id !== Auth::id()) {
            abort(403);
        }
        
        if (!$mlModel->isReadyForDeployment()) {
            return back()->with('error', 'Model must be trained and meet minimum accuracy requirements');
        }

        $mlModel->update([
            'is_active' => !$mlModel->is_active,
        ]);

        return back()->with('success', 'Model status updated');
    }

    /**
     * Delete a model
     */
    public function destroy(MLModel $mlModel)
    {
        if ($mlModel->user_id !== Auth::id()) {
            abort(403);
        }
        
        $mlModel->delete();

        return redirect()->route('ml-models.index')
            ->with('success', 'Model deleted successfully');
    }
}

    protected $mlModelService;

    public function __construct(MLModelService $mlModelService)
    {
        $this->mlModelService = $mlModelService;
    }

    /**
     * Display ML models dashboard
     */
    public function index()
    {
        $userId = Auth::id();
        
        $models = MLModel::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $activeModels = $models->where('is_active', true);
        $trainedModels = $models->where('status', 'trained');
        
        $stats = [
            'total' => $models->count(),
            'active' => $activeModels->count(),
            'trained' => $trainedModels->count(),
            'training' => $models->where('status', 'training')->count(),
        ];

        return view('ml-models.index', [
            'models' => $models,
            'stats' => $stats,
        ]);
    }

    /**
     * Show model creation form
     */
    public function create()
    {
        $modelTypes = [
            'price_direction' => 'Price Direction / Entry Signal',
            'volatility' => 'Volatility Forecasting',
            'sentiment' => 'Sentiment Analysis',
            'parameter_optimization' => 'Parameter Optimization',
        ];

        $architectures = [
            'TFT' => 'Temporal Fusion Transformer',
            'LSTM' => 'Long Short-Term Memory',
            'XGBoost' => 'XGBoost',
            'RandomForest' => 'Random Forest',
            'FinBERT' => 'FinBERT (NLP)',
            'Hybrid' => 'Hybrid Model',
            'GridSearch' => 'Grid Search',
        ];

        return view('ml-models.create', [
            'modelTypes' => $modelTypes,
            'architectures' => $architectures,
        ]);
    }

    /**
     * Store a new model
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:price_direction,volatility,sentiment,parameter_optimization',
            'architecture' => 'required|in:TFT,LSTM,XGBoost,RandomForest,FinBERT,Hybrid,GridSearch',
            'description' => 'nullable|string',
            'config' => 'nullable|array',
            'training_config' => 'nullable|array',
        ]);

        $model = $this->mlModelService->createModel(array_merge($validated, [
            'user_id' => Auth::id(),
            'status' => 'draft',
        ]));

        return redirect()->route('ml-models.show', $model)
            ->with('success', 'ML Model created successfully');
    }

    /**
     * Show model details
     */
    public function show(MLModel $mlModel)
    {
        if ($mlModel->user_id !== Auth::id()) {
            abort(403);
        }
        
        $recommendations = $this->mlModelService->getModelRecommendations($mlModel->type);
        $statistics = $this->mlModelService->getModelStatistics($mlModel);
        $recentPredictions = $mlModel->predictions()->latest()->limit(10)->get();
        $trainingLogs = $mlModel->trainingLogs()->latest()->get();

        return view('ml-models.show', [
            'model' => $mlModel,
            'recommendations' => $recommendations,
            'statistics' => $statistics,
            'recentPredictions' => $recentPredictions,
            'trainingLogs' => $trainingLogs,
        ]);
    }

    /**
     * Start training a model
     */
    public function startTraining(MLModel $mlModel)
    {
        if ($mlModel->user_id !== Auth::id()) {
            abort(403);
        }
        
        if ($mlModel->status !== 'draft' && $mlModel->status !== 'trained') {
            return back()->with('error', 'Model must be in draft or trained status to start training');
        }

        $this->mlModelService->startTraining($mlModel);

        // In a real implementation, you would queue a job here to actually train the model
        // For now, we'll just update the status

        return back()->with('success', 'Model training started');
    }

    /**
     * Toggle model active status
     */
    public function toggleActive(MLModel $mlModel)
    {
        if ($mlModel->user_id !== Auth::id()) {
            abort(403);
        }
        
        if (!$mlModel->isReadyForDeployment()) {
            return back()->with('error', 'Model must be trained and meet minimum accuracy requirements');
        }

        $mlModel->update([
            'is_active' => !$mlModel->is_active,
        ]);

        return back()->with('success', 'Model status updated');
    }

    /**
     * Delete a model
     */
    public function destroy(MLModel $mlModel)
    {
        if ($mlModel->user_id !== Auth::id()) {
            abort(403);
        }
        
        $mlModel->delete();

        return redirect()->route('ml-models.index')
            ->with('success', 'Model deleted successfully');
    }
}
