<!DOCTYPE html>
<html>
<head>
    <title>Auth Code Generator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        
        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        
        h2 {
            text-align: center;
        }
        
        label {
            display: block;
            margin-bottom: 10px;
        }
        
        select, button {
            display: block;
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        
        button {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Auth Code Generator</h2>
        <form action="save_authcodes.php" method="post">
            <label for="code_type">Code Type:</label>
            <select name="code_type" id="code_type">
                <option value="type1">Type 1</option>
                <option value="type2">Type 2</option>
                <option value="type3">Type 3</option>
            </select>
            <br><br>
            <button type="submit">Generate and Save Codes</button>
        </form>
    </div>
</body>
</html>
