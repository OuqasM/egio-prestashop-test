{extends file='page.tpl'}
{block name='page_content'}
    <form method="post" action="">
        <div class="form-group">
            <label for="city">City:</label>
            <input type="text" name="city" value="{$city}" class="form-control" />
        </div>
        <div class="form-group">
            <label for="forecastType">Forecast Type:</label>
            <select name="forecastType" class="form-control">
                <option value="today" {if $forecastType == 'today'}selected{/if}>Today</option>
                <option value="nextdays" {if $forecastType == 'nextdays'}selected{/if}>Next Days</option>
                <option value="nexthours" {if $forecastType == 'nexthours'}selected{/if}>Next Hours</option>
            </select>
        </div>
        <button type="submit" name="submitWeatherForm" class="btn btn-primary">Get Weather</button>
    </form>
    {if isset($weather_data.error)}
        <div class="alert alert-warning">
            {$weather_data.error}
        </div>
    {/if}
    {if $weather_data}
        {if $forecastType == 'today'}
            <h2>Weather in {$weather_data.city.name}</h2>
            <h3>Forecast :</h3>
            <p>Temperature: {$weather_data.forecast.tmin}°C</p>
            <p>Condition: {if $weather_data.forecast.weather == 0}Sunny{elseif $weather_data.forecast.weather == 1}Cloudy{elseif $weather_data.forecast.weather == 2}Rainy{elseif $weather_data.forecast.weather == 3}Snowy{else}Unknown{/if}</p>
            <p>Humidity: {$weather_data.forecast.rr10}%</p>
            <p>Wind Speed: {$weather_data.forecast.wind10m} km/h</p>
        {elseif $forecastType == 'nexthours'}
            <h3>Next Hours Forecast</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Temperature</th>
                        <th>Condition</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$weather_data.forecast item=hourForecast}
                        <tr>
                            <td>{$hourForecast.datetime|date_format:"%H:%M"}</td>
                            <td>{$hourForecast.temp2m}°C</td>
                            <td>{if $hourForecast.weather == 0}Sunny{elseif $hourForecast.weather == 1}Cloudy{elseif $hourForecast.weather == 2}Rainy{elseif $hourForecast.weather == 3}Snowy{else}Unknown{/if}</td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        {elseif $forecastType == 'nextdays'}
            <h3>Next Days Forecast</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Temperature</th>
                        <th>Condition</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$weather_data.forecast item=dayForecast}
                        <tr>
                            <td>{$dayForecast.datetime|date_format:"%Y-%m-%d"}</td>
                            <td>{$dayForecast.tmin}°C - {$dayForecast.tmax}°C</td>
                            <td>{if $dayForecast.weather == 0}Sunny{elseif $dayForecast.weather == 1}Cloudy{elseif $dayForecast.weather == 2}Rainy{elseif $dayForecast.weather == 3}Snowy{else}Unknown{/if}</td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        {/if}
    {/if}
{/block}
