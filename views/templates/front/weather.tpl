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
                            <td><a href="#" data-toggle="modal" data-target="#detailsModal-{$hourForecast.datetime|date_format:"%H:%M"}" >Details</a></td>

                            <!-- FIXME: this is a bad practice(a modal for each row), i don't have enough time to do it right -->
                            
                            <div class="modal fade" id="detailsModal-{$hourForecast.datetime|date_format:"%H:%M"}" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel-{$hourForecast.datetime|date_format:"%H:%M"}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="detailsModalLabel-{$hourForecast.datetime|date_format:"%H:%M"}">Details</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>
                                                Temperature: {$hourForecast.temp2m}°C<br>
                                                Condition: {if $hourForecast.weather == 0}Sunny{elseif $hourForecast.weather == 1}Cloudy{elseif $hourForecast.weather == 2}Rainy{elseif $hourForecast.weather == 3}Snowy{else}Unknown{/if}<br>
                                                Humidity: {$hourForecast.rr10} %<br>
                                                Wind Speed: {$hourForecast.wind10m} km/h
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$weather_data.forecast item=dayForecast}
                        <tr>
                            <td>{$dayForecast.datetime|date_format:"%Y-%m-%d"}</td>
                            <td>{$dayForecast.tmin}°C - {$dayForecast.tmax}°C</td>
                            <td>{if $dayForecast.weather == 0}Sunny{elseif $dayForecast.weather == 1}Cloudy{elseif $dayForecast.weather == 2}Rainy{elseif $dayForecast.weather == 3}Snowy{else}Unknown{/if}</td>
                            <td><a href="#" data-toggle="modal" data-target="#detailsModal-{$dayForecast.datetime|date_format:"%Y-%m-%d"}" >Details</a></td>
                            <div class="modal fade" id="detailsModal-{$dayForecast.datetime|date_format:"%Y-%m-%d"}" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel-{$dayForecast.datetime|date_format:"%Y-%m-%d"}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="detailsModalLabel-{$dayForecast.datetime|date_format:"%Y-%m-%d"}">Details</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>
                                                Temperature: {$dayForecast.tmin}°C - {$dayForecast.tmax}°C<br>
                                                Condition: {if $dayForecast.weather == 0}Sunny{elseif $dayForecast.weather == 1}Cloudy{elseif $dayForecast.weather == 2}Rainy{elseif $dayForecast.weather == 3}Snowy{else}Unknown{/if}<br>
                                                Humidity: {$dayForecast.rr10} %<br>
                                                Wind Speed: {$dayForecast.wind10m} km/h
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        {/if}
    {/if}
{/block}

