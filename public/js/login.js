// Tạo ngôi sao
function createStar() {
	const star = document.createElement('div');
	star.className = 'star';
	
	// Kích thước ngẫu nhiên
	const size = Math.random() * 4 + 1;
	star.style.width = size + 'px';
	star.style.height = size + 'px';
	
	// Vị trí ngẫu nhiên
	star.style.left = Math.random() * 100 + '%';
	star.style.top = Math.random() * 100 + '%';
	
	// Thời gian lấp lánh ngẫu nhiên
	star.style.animationDuration = (Math.random() * 3 + 1) + 's';
	star.style.animationDelay = Math.random() * 2 + 's';
	
	document.body.appendChild(star);
}

// Tạo sao băng
function createShootingStar() {
	const shootingStar = document.createElement('div');
	shootingStar.className = 'shooting-star';
	
	shootingStar.style.width = Math.random() * 80 + 50 + 'px';
	shootingStar.style.left = '-100px';
	shootingStar.style.top = Math.random() * 50 + '%';
	shootingStar.style.animationDelay = Math.random() * 5 + 's';
	
	document.body.appendChild(shootingStar);
	
	setTimeout(() => {
		if (shootingStar.parentNode) {
			shootingStar.parentNode.removeChild(shootingStar);
		}
	}, 8000);
}

// Tạo đám mây
function createCloud() {
	const cloud = document.createElement('div');
	cloud.className = 'cloud';
	
	const width = Math.random() * 100 + 50;
	const height = Math.random() * 20 + 10;
	
	cloud.style.width = width + 'px';
	cloud.style.height = height + 'px';
	cloud.style.left = '-100px';
	cloud.style.top = Math.random() * 70 + '%';
	cloud.style.animationDuration = (Math.random() * 30 + 20) + 's';
	
	// Tạo hình dạng đám mây
	cloud.style.setProperty('--before-width', (width * 0.6) + 'px');
	cloud.style.setProperty('--before-height', (height * 0.8) + 'px');
	cloud.style.setProperty('--after-width', (width * 0.8) + 'px');
	cloud.style.setProperty('--after-height', (height * 0.6) + 'px');
	
	cloud.innerHTML = `
		<style>
		.cloud::before {
			width: ${width * 0.6}px;
			height: ${height * 0.8}px;
			left: -${width * 0.2}px;
			top: -${height * 0.1}px;
		}
		.cloud::after {
			width: ${width * 0.8}px;
			height: ${height * 0.6}px;
			right: -${width * 0.2}px;
			top: -${height * 0.1}px;
		}
		</style>
	`;
	
	document.body.appendChild(cloud);
	
	setTimeout(() => {
		if (cloud.parentNode) {
			cloud.parentNode.removeChild(cloud);
		}
	}, 50000);
}

// Tạo 200 ngôi sao
for (let i = 0; i < 200; i++) {
	createStar();
}

// Tạo sao băng định kỳ
setInterval(createShootingStar, 3000);

// Tạo đám mây định kỳ
setInterval(createCloud, 15000);

// Tạo vài đám mây ban đầu
createCloud();
setTimeout(createCloud, 5000);
setTimeout(createCloud, 10000);

// Thêm hiệu ứng click cho logo Google
document.querySelector('.google-logo').addEventListener('click', function() {
	this.style.transform = 'scale(0.95)';
	setTimeout(() => {
		this.style.transform = 'scale(1.1)';
	}, 150);
});