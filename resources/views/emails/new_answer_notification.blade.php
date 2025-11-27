<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle réponse</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 0 0 5px 5px;
        }
        .answer-box {
            background-color: white;
            padding: 15px;
            border-left: 4px solid #4CAF50;
            margin: 15px 0;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📝 Nouvelle Réponse !</h1>
    </div>
    
    <div class="content">
        <p>Bonjour,</p>
        
        <p>Une nouvelle réponse a été soumise sur votre sondage : <strong>{{ $survey->title }}</strong></p>
        
        <div class="answer-box">
            <p><strong>Question :</strong> {{ $answer->question->title ?? 'Question supprimée' }}</p>
            <p><strong>Réponse :</strong></p>
            <p>{{ is_string($answer->answer) ? $answer->answer : json_encode($answer->answer) }}</p>
            <p><strong>Date :</strong> {{ $answer->created_at->format('d/m/Y à H:i') }}</p>
            @if($answer->user)
                <p><strong>Utilisateur :</strong> {{ $answer->user->name }}</p>
            @else
                <p><em>Réponse anonyme</em></p>
            @endif
        </div>
        
        <a href="{{ route('surveys.index') }}" class="button">
            Voir tous les résultats
        </a>
    </div>
    
    <div class="footer">
        <p>Vous recevez cet email car vous êtes le créateur du sondage.</p>
        <p>Pour désactiver ces notifications, modifiez vos préférences dans votre profil.</p>
    </div>
</body>
</html>