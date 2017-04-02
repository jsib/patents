</div>

			<!--START: Код календаря. Эту штуку надо ставить именно здесь, перед закрывающим body-->
			<script>
			$(".datepickerTimeField").datepicker({
					changeMonth: true,
					changeYear: true,
					dateFormat: 'dd.mm.yy',
					firstDay: 1, changeFirstDay: false,
					navigationAsDateFormat: false,
					duration: 0,// отключаем эффект появления
			});
			</script>
			<!--Используется так <input name="min" value="04.05.2010" class="datepickerTimeField">-->
			<!--Взять здесь http://yapro.ru/web-master/javascript/legkiy-kalendari.html-->
			<!--END: Код календаря-->


</body>
</html>

