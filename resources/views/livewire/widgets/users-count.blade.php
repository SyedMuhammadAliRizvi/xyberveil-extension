<div class="row">
    <div class="col-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h3 class="card-title mb-0">Recent Users</h3>
            </div>
            <div class="card-body">
                <table class="table table-responsive">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentUsers as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->toFormattedDateString() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No users found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-4 d-flex">
        <div class="card radius-10 w-100">
            <div class="card-body">
                <p class="font-weight-bold mb-1">New Visitors</p>
                <div class="d-flex align-items-center">
                    <div>
                        <h4 class="mb-0">{{ number_format(array_sum($counts)) }}</h4>
                    </div>
                    <div class="">
                        <p class="mb-0 align-self-center font-weight-bold ms-2">4.4
                            <i class='bx bxs-up-arrow-alt mr-2'></i>
                        </p>
                    </div>
                </div>
                <div id="chart21" style="max-height: 300px"></div>
            </div>
        </div>
    </div>
</div>
@script
    <script>
        const isDarkTheme = document.documentElement.getAttribute('data-bs-theme') === 'dark';

        const options = {
            series: [{
                name: "Visitors",
                data: @json($counts)
            }],
            chart: {
                foreColor: isDarkTheme ? "rgba(255, 255, 255, 0.50)" : "rgba(15, 23, 42, 0.65)",
                type: "bar",
                height: 390,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                },
                dropShadow: {
                    enabled: false,
                    top: 3,
                    left: 10,
                    blur: 3,
                    opacity: 0.1,
                    color: "#0070FF"
                },
                sparkline: {
                    enabled: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: "35%",
                    endingShape: "rounded"
                }
            },
            markers: {
                size: 0,
                colors: [isDarkTheme ? "#fff" : "#0070FF"],
                strokeColors: isDarkTheme ? "#fff" : "#0070FF",
                strokeWidth: 2,
                hover: {
                    size: 7
                }
            },
            dataLabels: {
                enabled: false
            },
            grid: {
                borderColor: isDarkTheme ? 'rgba(255, 255, 255, 0.12)' : 'rgba(15, 23, 42, 0.08)',
                show: true,
            },
            stroke: {
                show: true,
                width: 3,
                curve: "smooth"
            },
            colors: [isDarkTheme ? "#fff" : "#0070FF"],
            xaxis: {
                categories: @json($months)
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                theme: isDarkTheme ? "dark" : "light",
                y: {
                    formatter: function(val) {
                        return val + " users";
                    }
                }
            }
        };

        const NewUsersChart = new ApexCharts(document.querySelector("#chart21"), options).render();
    </script>
@endscript
