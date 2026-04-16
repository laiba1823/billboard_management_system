@extends('public.layouts.parent')

@section('title', "Buyer | CAN")

@section('banner')
	<!--Inner Home Banner Start-->
	<div class="wt-haslayout wt-innerbannerholder wt-innerbannerholdervtwo">
		<div class="container">
			<div class="row justify-content-md-center">
				<div class="col-xs-12 col-sm-12 col-md-8 push-md-2 col-lg-6 push-lg-3">
				</div>
			</div>
		</div>
	</div>
	<!--Inner Home End-->
@endsection

@section('content')
	<!--Main Start-->
	<main id="wt-main" class="wt-main wt-haslayout wt-innerbgcolor">
		<!-- User Profile Start-->
		<div class="wt-main-section wt-paddingtopnull wt-haslayout">
			<div class="container">
				<div class="row">
					<div class="col-12 col-sm-12 col-md-12 col-lg-12 float-left">
						<div class="wt-userprofileholder">
							<span class="wt-featuredtag"><img src="{{asset("images/featured.png") }}" alt="img description" data-tipso="Plus Member" class="template-content tipso_style"></span>
							<div class="col-12 col-sm-12 col-md-12 col-lg-3 float-left">
								<div class="row">
									<div class="wt-userprofile">
										<figure>
											<img src="{{ asset($buyer->img) }}" alt="img description">
											<div class="wt-userdropdown wt-online">
											</div>
										</figure>
										<div class="wt-title">
											<h3><i class="fa fa-check-circle"></i> Verified</h3>
											<span>
												<a href="javascript:void(0);">@ {{$buyer->name}}</a>
												<a href="javascript:void(0);" class="wt-reportuser">Report User</a></span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-12 col-sm-12 col-md-12 col-lg-9 float-left">
								<div class="row">
									<div class="wt-proposalhead wt-userdetails">
										<h2>{{ $buyer->name }}</h2>
										<ul class="wt-userlisting-breadcrumb wt-userlisting-breadcrumbvtwo">
											<li><span>{{ $buyer->address ?: 'Pakistan' }}</span></li>
										</ul>
										<div class="wt-description">
											<p>{{ $buyer->about ?? 'No description available.' }}</p>
										</div>
									</div>
									<div id="wt-statistics" class="wt-statistics wt-profilecounter">
										<div class="wt-statisticcontent wt-countercolor1">
											<h3 data-from="0" data-to="{{ $buyer->orders()->where('status', 'pending')->count() }}" data-speed="800" data-refresh-interval="03">{{ $buyer->orders()->where('status', 'pending')->count() }}</h3>
											<h4>Active <br>Orders</h4>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- User Profile End-->
		</div>
	</main>
@endsection