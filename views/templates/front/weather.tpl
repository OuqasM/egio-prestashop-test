<form method="post" action="">
    <div class="form-group">
        <label for="city">City:</label>
        <input type="text" name="city" value="{$city}" class="form-control" />
    </div>
    <button type="submit" name="submitWeatherForm" class="btn btn-primary">Get Weather</button>
</form>

{if $weather_data}
    <h2>Weather in {$city}</h2>
    <p>Temperature:  {$weather_data.current.temp}°C</p>
    <p>Condition: {$weather_data.current.condition}</p>
{/if}
